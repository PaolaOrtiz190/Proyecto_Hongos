const app = express();
const server = http.createServer(app);
const io = socketIo(server);

const port = new SerialPort({ path: 'COM9', baudRate: 9600 });
const parser = port.pipe(new ReadlineParser({ delimiter: '\n' }));

app.use(express.static('public'));

const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'hongos'
});

connection.connect((err) => {
  if (err) {
    console.error('Error de conexión: ', err);
    return;
  }
  console.log('Conexión exitosa a la base de datos');
});

parser.on('data', (data) => {
  io.emit('arduinoData', data);
  console.log(data);
  const values = data.split('\t');
  console.log('values', values);
  if (values.length > 0) {

    console.log("valores", values);
    const query = 'INSERT INTO hongos (fecha, hora, temp1, temp2, temp3, temp4, temp5, t_ext, hum_ext, tempAvg, hnumAvg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

    connection.query(query, values, (err, result) => {
      if (err) {
        console.error('Error al insertar datos:', err);
        return;
      }
      console.log('Datos insertados:', result);
    });
  }
});

io.on('connection', (socket) => {
  console.log('New client connected');
  socket.on('disconnect', () => {
    console.log('Client disconnected');
  });
});

// Nueva ruta para obtener los datos de la base de datos
app.get('/api/hongos', (req, res) => {
  const { startDate, endDate } = req.query;
  let query = 'SELECT * FROM hongos';
  const queryParams = [];

  if (startDate && endDate) {
    query += ' WHERE fecha BETWEEN ' + startDate + ' AND ' + endDate;
    queryParams.push(startDate, endDate);
  }

  query += ' ORDER BY fecha ASC';

  connection.query(query, queryParams, (err, results) => {
    if (err) {
      console.error('Error al obtener datos:', err);
      res.status(500).send('Error al obtener datos');
      return;
    }
    res.json(results);
  });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => console.log('Server running on port ' + PORT + '...'));
