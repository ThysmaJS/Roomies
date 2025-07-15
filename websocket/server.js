const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 3001 });

const rooms = {};

wss.on('connection', (ws) => {
  let currentRoom = null;

  ws.on('message', (message) => {
    try {
      const data = JSON.parse(message);

      if (data.type === 'join') {
        currentRoom = data.room;
        if (!rooms[currentRoom]) rooms[currentRoom] = [];
        rooms[currentRoom].push(ws);
        console.log(`✅ Client joined room: ${currentRoom}`);
        // 👉 Optionnel : notifier les autres
        const joinMessage = {
          type: 'system',
          text: `${data.user || 'Un utilisateur'} a rejoint la room`
        };
        rooms[currentRoom].forEach(client => {
          if (client.readyState === WebSocket.OPEN) {
            client.send(JSON.stringify(joinMessage));
          }
        });
      }

      if (data.type === 'message' && currentRoom) {
        const messageData = {
          type: 'message',
          user: data.user,
          text: data.text
        };

        // ✅ TOUS les clients reçoivent un message JSON stringifié
        rooms[currentRoom].forEach(client => {
          if (client.readyState === WebSocket.OPEN) {
            client.send(JSON.stringify(messageData));
          }
        });
      }
    } catch (err) {
      console.error('❌ Erreur JSON WebSocket :', err.message);
    }
  });

  ws.on('close', () => {
    if (currentRoom && rooms[currentRoom]) {
      rooms[currentRoom] = rooms[currentRoom].filter(client => client !== ws);
    }
  });
});
