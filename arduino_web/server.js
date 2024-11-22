const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const { SerialPort } = require('serialport');
const { ReadlineParser } = require('@serialport/parser-readline');
const mysql = require('mysql');

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
  console.log(data);
  io.emit('arduinoData', data);

  const values = data.split('\t');
  const query = 'INSERT INTO hongos (fecha, hora, temp1, temp2, temp3, temp4, temp5, t_ext, hum_ext, tempAvg, hnumAvg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

  connection.query(query, values, (err, result) => {
    if (err) {
      console.error('Error al insertar datos:', err);
      return;
    }
    console.log('Datos insertados:', result);
  });
});

io.on('connection', (socket) => {
  console.log('New client connected');
  socket.on('disconnect', () => {
    console.log('Client disconnected');
  });
});

app.get('/data', (req, res) => {
  connection.query('SELECT * FROM hongos', (err, rows) => {
    if (err) {
      console.error('Error en la consulta:', err);
      res.status(500).send('Error en la consulta');
      return;
    }
    res.json(rows);
  });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => console.log(`Server running on port ${PORT}`));