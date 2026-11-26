import React from 'react'
import ReactDOM from 'react-dom/client'
import { BrowserRouter } from 'react-router-dom'
import App from './App'
import { AuthProvider } from './context/AuthContext'
import './index.css'
import '@fontsource/press-start-2p/400.css'
import 'flag-icons/css/flag-icons.min.css';
import { ToastProvider } from './context/ToastContext';
import { DeseadosProvider } from './context/DeseadosContext';

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <BrowserRouter>
	    <AuthProvider>
			<ToastProvider>
				<DeseadosProvider>
					<App />
				</DeseadosProvider>
			</ToastProvider>
		</AuthProvider>
    </BrowserRouter>
  </React.StrictMode>
)
