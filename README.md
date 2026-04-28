# IPO Management System

A professional IPO (Initial Public Offering) management platform built with Flask, MySQL, and modern dark-themed UI.

## 🚀 Features

### User Features
- **Secure Authentication**: Strong password requirements (12+ chars, uppercase, lowercase, number, special character)
- **Wallet Management**: Add funds with validation (Min: ₹1, Max: ₹10,00,000 per transaction)
- **IPO Explorer**: Browse and apply for available IPOs
- **Portfolio Tracking**: View holdings with real-time P&L calculations
- **Application History**: Track IPO application status and allotments
- **Transaction History**: Complete audit trail of all wallet activities

### Admin Features
- **Dashboard**: Overview of all applications and statistics
- **IPO Management**: Create and manage IPO offerings
- **Application Approval**: Review and approve/reject applications with validation
- **Real-time Statistics**: Track pending, approved, and total applications

### Security & Validation
- **Password Hashing**: Bcrypt encryption for all passwords
- **ACID Transactions**: Database transactions for financial operations
- **Input Validation**: 
  - Wallet recharge: ₹1 - ₹10,00,000 per transaction
  - Max wallet balance: ₹9,99,99,999.99
  - Max shares per application: 10,000
  - Balance verification before transactions
- **Session Management**: Secure user and admin sessions

## 🎨 UI/UX

Professional dark theme with institutional design:
- Dark navy background (#0a0e27, #141b3a, #1a2142)
- Blue accent (#4c6fff) for primary actions
- Green (#00d4aa) for positive indicators
- Red (#ff4757) for negative indicators
- Responsive sidebar navigation
- Bottom-right notifications
- Card-based layouts
- Modern typography (Inter font)

## 📋 Prerequisites

- Python 3.8+
- MySQL 8.0+
- pip (Python package manager)

## 🛠️ Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd Ipo_Management_system
```

2. **Install Python dependencies**
```bash
pip install -r requirements.txt
```

3. **Configure environment variables**

Create a `.env` file in the root directory:
```env
SECRET_KEY=your-secret-key-here
MYSQL_HOST=localhost
MYSQL_USER=root
MYSQL_PASSWORD=Tanishpoddar.18
MYSQL_DB=ipo_management_system
```

4. **Initialize the database**
```bash
python init_db.py
```

This will:
- Create the database and all tables
- Set up indexes for performance
- Insert sample IPOs
- Create test accounts

## 🚀 Running the Application

```bash
python app.py
```

The application will be available at: `http://127.0.0.1:5000`

## 🔑 Test Credentials

### Admin Portal
- **URL**: `http://127.0.0.1:5000/admin/login`
- **Username**: `admin`
- **Password**: `Admin@123456`

### User Portal
- **URL**: `http://127.0.0.1:5000/login`

**Test User 1:**
- **Email**: `test@example.com`
- **Password**: `Test@123456`
- **Balance**: ₹1,00,000

**Test User 2:**
- **Email**: `demo@example.com`
- **Password**: `Test@123456`
- **Balance**: ₹50,000

## 📁 Project Structure

```
Ipo_Management_system/
├── app.py                      # Main Flask application
├── init_db.py                  # Database initialization script
├── requirements.txt            # Python dependencies
├── .env                        # Environment variables
├── static/
│   ├── css/
│   │   └── style.css          # Dark theme styles
│   └── js/
│       └── main.js            # Frontend JavaScript
├── templates/
│   ├── base.html              # Base template
│   ├── index.html             # Landing page
│   ├── login.html             # User login
│   ├── register.html          # User registration
│   ├── admin/
│   │   ├── login.html         # Admin login
│   │   ├── dashboard.html     # Admin dashboard
│   │   └── add_ipo.html       # Create IPO form
│   └── user/
│       ├── home.html          # User home
│       ├── dashboard.html     # User dashboard
│       ├── ipos.html          # IPO explorer
│       ├── wallet.html        # Wallet management
│       ├── portfolio.html     # Holdings view
│       └── applications.html  # Application history
└── README.md
```

## 🗄️ Database Schema

### Tables
- **users**: User accounts with encrypted passwords
- **admin**: Admin accounts
- **ipo**: IPO offerings
- **ipo_application**: User applications
- **allotment**: Share allotments
- **portfolio**: User holdings
- **wallet_transactions**: Transaction history

### Key Features
- Foreign key constraints for data integrity
- Indexes for query performance
- ACID transaction support
- Cascade deletes for cleanup

## 🔒 Security Features

1. **Password Requirements**:
   - Minimum 12 characters
   - At least one uppercase letter
   - At least one lowercase letter
   - At least one number
   - At least one special character

2. **Financial Validations**:
   - Wallet recharge limits
   - Balance verification
   - Share availability checks
   - Transaction rollback on errors

3. **Session Security**:
   - Secure session management
   - Login required decorators
   - Admin access control

## 🎯 Usage Flow

### For Users
1. Register with a strong password
2. Login to access dashboard
3. Add funds to wallet (₹1 - ₹10,00,000 per transaction)
4. Browse available IPOs
5. Apply for IPOs with desired shares
6. Track application status
7. View portfolio and P&L

### For Admins
1. Login to admin portal
2. View application queue
3. Create new IPO offerings
4. Approve/reject applications
5. Monitor system statistics

## 🐛 Troubleshooting

### Database Connection Issues
```bash
# Test MySQL connection
mysql -u root -p
# Enter password: Tanishpoddar.18
```

### Reinitialize Database
```bash
python init_db.py
```

### Common Errors
- **"Out of range value for column 'balance'"**: Wallet balance exceeds maximum limit
- **"Insufficient balance"**: Add funds to wallet before applying
- **"Invalid password"**: Ensure password meets all requirements

## 📝 API Routes

### Public Routes
- `GET /` - Landing page
- `GET /login` - User login page
- `POST /login` - User login handler
- `GET /register` - Registration page
- `POST /register` - Registration handler
- `GET /admin/login` - Admin login page
- `POST /admin/login` - Admin login handler

### User Routes (Login Required)
- `GET /user/home` - User home
- `GET /user/dashboard` - User dashboard
- `GET /user/ipos` - IPO explorer
- `POST /user/apply` - Apply for IPO
- `GET /user/wallet` - Wallet page
- `POST /user/wallet` - Add funds
- `GET /user/portfolio` - Portfolio view
- `GET /user/applications` - Application history

### Admin Routes (Admin Login Required)
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/add_ipo` - Add IPO form
- `POST /admin/add_ipo` - Create IPO
- `POST /admin/approve` - Approve application

### Common Routes
- `GET /logout` - Logout

## 🔄 Updates & Maintenance

### Adding New IPOs
1. Login as admin
2. Navigate to "Add IPO"
3. Fill in company details
4. Set price and share quantities
5. Define open/close dates

### Managing Applications
1. View pending applications in admin dashboard
2. Review user details and requested shares
3. Approve with allotted shares (can be less than applied)
4. System automatically:
   - Deducts user balance
   - Updates portfolio
   - Records transaction
   - Reduces available IPO shares

## 📊 Features in Detail

### Wallet System
- Secure fund management
- Transaction history with timestamps
- Real-time balance updates
- Validation at multiple levels

### IPO Application
- Real-time share availability
- Balance verification
- Transaction rollback on failure
- Detailed error messages

### Portfolio Management
- Real-time P&L calculation
- Average price tracking
- Current value computation
- Percentage gain/loss display

### Admin Controls
- Application queue management
- Flexible approval system
- Partial allotment support
- Comprehensive validation

## 🤝 Contributing

This is a complete, production-ready IPO management system with:
- ✅ Secure authentication
- ✅ Financial transaction handling
- ✅ Professional UI/UX
- ✅ Complete validation
- ✅ Error handling
- ✅ Responsive design

## 📄 License

This project is for educational and demonstration purposes.

## 👨‍💻 Developer

Built with Flask, MySQL, and modern web technologies.

---

**Note**: This system includes test credentials for demonstration. In production, ensure all default credentials are changed and proper security measures are implemented.
