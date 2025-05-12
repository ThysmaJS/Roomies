# Roomies - Frontend

A modern real-time chat application built with Vue 3, TypeScript, Pinia, and Socket.IO.

## Features

- 🚀 Real-time messaging with WebSockets
- 🔐 User authentication and authorization
- 💬 Group chat rooms
- 👤 User profiles
- 🎨 Responsive design
- 📱 Mobile-friendly interface
- 🛠 TypeScript support
- 🏗 Component-based architecture

## Tech Stack

- **Framework**: Vue 3 with Composition API
- **State Management**: Pinia
- **Routing**: Vue Router 4
- **Styling**: CSS3 with CSS Variables
- **HTTP Client**: Axios
- **Real-time**: Socket.IO
- **Build Tool**: Vite
- **Linting**: ESLint + Prettier
- **Type Checking**: TypeScript

## Prerequisites

- Node.js 18+ and npm/yarn/pnpm
- Backend API server (see backend repository)

## Project Structure

```
frontend/
├── public/                  # Static files
├── src/
│   ├── assets/             # Images, fonts, etc.
│   ├── components/          # Reusable components
│   │   ├── Chat/            # Chat related components
│   │   ├── Room/            # Room related components
│   │   └── User/            # User related components
│   ├── pages/               # Page components
│   ├── router/              # Vue Router configuration
│   ├── services/            # API services
│   ├── sockets/             # WebSocket services
│   ├── store/               # Pinia stores
│   ├── types/               # TypeScript type definitions
│   ├── App.vue              # Root component
│   └── main.ts              # Application entry point
├── .env.example             # Environment variables example
├── .gitignore               # Git ignore file
├── package.json             # Project dependencies
├── tsconfig.json            # TypeScript configuration
└── vite.config.ts           # Vite configuration
```

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/yourusername/roomies.git
cd roomies/frontend
```

### 2. Install dependencies

```bash
# Using npm
npm install

# Using yarn
yarn

# Using pnpm
pnpm install
```

### 3. Configure environment variables

Copy the example environment file and update the values:

```bash
cp .env.example .env
```

Update the `.env` file with your backend API URL and WebSocket URL.

### 4. Start the development server

```bash
# Using npm
npm run dev

# Using yarn
yarn dev

# Using pnpm
pnpm dev
```

Open [http://localhost:5173](http://localhost:5173) to view it in your browser.

### 5. Build for production

```bash
# Using npm
npm run build

# Using yarn
yarn build

# Using pnpm
pnpm build
```

## Available Scripts

- `dev` - Start development server with hot-reload
- `build` - Build for production
- `preview` - Preview production build locally
- `lint` - Lint code with ESLint
- `type-check` - Run TypeScript type checking

## Development

### Recommended IDE Setup

- [VSCode](https://code.visualstudio.com/) + [Volar](https://marketplace.visualstudio.com/items?itemName=Vue.volar) (and disable Vetur)
- [TypeScript Vue Plugin (Volar)](https://marketplace.visualstudio.com/items?itemName=Vue.vscode-typescript-vue-plugin)

### Type Support for `.vue` Imports in TS

TypeScript cannot handle type information for `.vue` imports by default. We use `vue-tsc` for type checking and [Volar](https://marketplace.visualstudio.com/items?itemName=Vue.volar) for editor integration.

## Deployment

### Building for Production

1. Run the build command:
   ```bash
   npm run build
   ```

2. The build artifacts will be stored in the `dist/` directory.

### Docker

You can also build and run the application using Docker:

```bash
# Build the Docker image
docker build -t roomies-frontend .

# Run the container
docker run -p 80:80 roomies-frontend
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Vue.js and Vue Team for the amazing framework
- Vite for the fast development experience
- Socket.IO for real-time communication
- All contributors and open-source maintainers
