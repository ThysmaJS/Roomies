const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 3001 });

const rooms = {};

/**
 * Structure rooms = {
 *   roomId: {
 *     clients: [ws, ws, ...],
 *     players: { X: username, O: username },
 *     board: ['', '', '', '', '', '', '', '', ''],
 *     currentPlayer: 'X' or 'O',
 *     winner: null or 'X' or 'O'
 *   }
 * }
 */

wss.on('connection', (ws) => {
  let currentRoom = null;
  let mySymbol = null;
  let myUsername = null;

  ws.on('message', (message) => {
    try {
      const data = JSON.parse(message);

      // --- JOIN ROOM ---
      if (data.type === 'join') {
        currentRoom = data.room;
        myUsername = data.user;

        if (!rooms[currentRoom]) {
          rooms[currentRoom] = {
            clients: [],
            players: {},
            board: Array(9).fill(''),
            currentPlayer: 'X',
            winner: null
          };
        }

        // Ajoute le client à la room
        rooms[currentRoom].clients.push(ws);

        // Attribution X/O
        if (!rooms[currentRoom].players.X) {
          rooms[currentRoom].players.X = myUsername;
          mySymbol = 'X';
        } else if (!rooms[currentRoom].players.O) {
          rooms[currentRoom].players.O = myUsername;
          mySymbol = 'O';
        } else {
          mySymbol = null; // spectateur
        }

        // Notifie le client de son symbole
        ws.send(JSON.stringify({
          type: 'symbol',
          symbol: mySymbol
        }));

        // Diffuse l'état de la partie à tout le monde
        broadcastRoomState(currentRoom);

        // Message système
        broadcast(currentRoom, {
          type: 'system',
          text: `${myUsername} a rejoint la room (${mySymbol || "spectateur"})`
        });
      }

      // --- JEU (COUP) ---
      if (data.type === 'play' && currentRoom && mySymbol) {
        const room = rooms[currentRoom];
        if (!room || room.winner) return; // Partie finie
        if (room.currentPlayer !== mySymbol) return; // Pas ton tour
        if (room.board[data.index]) return; // Case déjà prise

        // Joue le coup
        room.board[data.index] = mySymbol;

        // Vérifie gagnant
        const wins = [
          [0,1,2],[3,4,5],[6,7,8],[0,3,6],[1,4,7],[2,5,8],[0,4,8],[2,4,6]
        ];
        let hasWinner = false;
        for (const [a,b,c] of wins) {
          if (room.board[a] && room.board[a] === room.board[b] && room.board[a] === room.board[c]) {
            room.winner = room.board[a];
            hasWinner = true;
            break;
          }
        }

        // Change de joueur si pas fini
        if (!hasWinner) {
          room.currentPlayer = room.currentPlayer === 'X' ? 'O' : 'X';
        }

        broadcastRoomState(currentRoom);
      }

      // --- CHAT ---
      if (data.type === 'message' && currentRoom) {
        const chatMsg = {
          type: 'message',
          user: data.user,
          text: data.text
        };
        rooms[currentRoom]?.clients.forEach(client => {
          if (client.readyState === WebSocket.OPEN) {
            client.send(JSON.stringify(chatMsg));
          }
        });
      }

    } catch (err) {
      console.error('❌ Erreur JSON WebSocket :', err.message);
    }
  });

  ws.on('close', () => {
    if (currentRoom && rooms[currentRoom]) {
      rooms[currentRoom].clients = rooms[currentRoom].clients.filter(client => client !== ws);
      // Optionnel : reset room si tout le monde est parti
      if (rooms[currentRoom].clients.length === 0) {
        delete rooms[currentRoom];
      }
    }
  });
});

// Envoie l'état complet de la partie à tous les clients d'une room
function broadcastRoomState(roomId) {
  const room = rooms[roomId];
  if (!room) return;
  const msg = {
    type: 'board',
    board: room.board,
    currentPlayer: room.currentPlayer,
    winner: room.winner,
    players: room.players
  };
  broadcast(roomId, msg);
}

// Diffuse à tous
function broadcast(roomId, obj) {
  const room = rooms[roomId];
  if (!room) return;
  room.clients.forEach(ws => {
    if (ws.readyState === WebSocket.OPEN) {
      ws.send(JSON.stringify(obj));
    }
  });
}
