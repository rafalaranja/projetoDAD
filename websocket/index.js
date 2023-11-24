const httpServer = require('http').createServer()
const io = require("socket.io")(httpServer, {
 cors: {
 // The origin is the same as the Vue app domain. Change if necessary
 origin: "http://localhost:5173",
 methods: ["GET", "POST"],
 credentials: true
 }
})
httpServer.listen(8080, () =>{
 console.log('listening on *:8080')
})
io.on('connection', (socket) => {
 console.log(`client ${socket.id} has connected`)
 socket.on('Login',(login)=>{
    socket.broadcast.emit('Login',login)
 })
})