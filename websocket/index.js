const httpServer = require("http").createServer();
const io = require("socket.io")(httpServer, {
  cors: {
    // The origin is the same as the Vue app domain. Change if necessary
    origin: "http://localhost:5173",
    methods: ["GET", "POST"],
    credentials: true,
  },
});
const connectedUser={};
httpServer.listen(8080, () => {
  console.log("listening on *:8080");
});
io.on("connection", (socket) => {
  console.log(`client ${socket.id} has connected`);
  //   socket.on("loggedIn", function (user) {
  //     socket.join(user.id);
  //     if (user.type == "A") {
  //       socket.join("administrator");
  //     }
  //     console.log("User logged in:", user.name);
  //   });
  //   socket.on("loggedOut", function (user) {
  //     socket.leave(user.id);
  //     socket.leave("administrator");
  //   });
  socket.on("ask",function(ask){
    console.log("ask",ask);
    try{
    const recipientSocketId = connectedUser[ask.vcard_dest]
    io.to(recipientSocketId).emit("askResponse",ask);
    } catch (error) {
    console.error("Erro ao enviar ask para o usuário de destino:", error.message);
    }
  });
  socket.on("login", function (user) {
    console.log("login", user.id);
    connectedUser[user.id] = socket.id;
    socket.emit("loggedIn", user);
  });

  socket.on("logout", function (user) {
    console.log("logout", user);
    delete connectedUser[user.id];
    socket.emit("loggedOut", user);
  });
});
