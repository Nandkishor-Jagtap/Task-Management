# Task Manager - PHP Application

A mid-level PHP task management system with user management, database integration, and REST API endpoints.

## Features
- User management (Create, Read, Update, Delete)
- Task management with status tracking (pending, in_progress, completed)
- REST API endpoints for mobile/frontend integration
- MySQL database integration
- Responsive web interface
- Unit tests with PHPUnit
- MVC architecture

## Requirements
- PHP 8.0+
- MySQL 5.7+
- Composer
- Web server (Apache/Nginx) or PHP built-in server

## Installation

### 1. Install Dependencies
```bash
composer install
```

### 2. Database Setup
1. Create MySQL database
2. Import the database schema:
```bash
mysql -u root -p < database.sql
```

### 3. Configuration
1. Copy `.env.example` to `.env`
2. Update database credentials in `.env` file

### 4. Run Application
```bash
# Development server
composer start

# Or with PHP built-in server
php -S localhost:8000 -t public
```

## Usage

### Web Interface
- **Dashboard**: `http://localhost:8000/` - Overview of tasks and statistics
- **Tasks**: `http://localhost:8000/tasks` - Task management interface
- **Create Task**: `http://localhost:8000/tasks/create` - Add new tasks

### API Endpoints
- `GET /api/health` - Health check
- `GET /api/tasks` - Get all tasks
- `GET /api/users` - Get all users
- `POST /api/tasks` - Create new task (future enhancement)
- `PUT /api/tasks/{id}` - Update task status (future enhancement)

### Example API Response
```json
{
  "status": "ok",
  "timestamp": "2024-01-15T10:30:45+00:00",
  "database": "connected"
}
```

## Testing
```bash
composer test
```

## Project Structure
```
task-manager/
├── public/                 # Web accessible files
│   ├── index.php          # Entry point
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
├── src/                   # Application source code
│   ├── Config/            # Configuration classes
│   ├── Models/            # Data models
│   ├── Controllers/       # Request handlers
│   ├── Utils/             # Utility classes
│   ├── Router.php         # URL routing
│   └── App.php            # Main application class
├── tests/                 # Unit tests
├── composer.json          # Dependencies
├── database.sql           # Database schema
└── README.md              # This file
```

## Technologies Used
- PHP 8.0+ with PDO for database access
- MySQL for data persistence
- PHPUnit for testing
- Composer for dependency management
- Vanilla CSS for styling
- Vanilla JavaScript for interactivity

## Future Enhancements
- User authentication and authorization
- Task categories and tags
- File attachments
- Email notifications
- Advanced filtering and search
- REST API for mobile apps
- Docker containerization
