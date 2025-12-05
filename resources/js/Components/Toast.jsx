import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';

const ToastContext = React.createContext();

export const useToast = () => React.useContext(ToastContext);

export function ToastProvider({ children }) {
  const [toasts, setToasts] = useState([]);

  const addToast = (message, type = 'info', duration = 3000) => {
    const id = Date.now();
    setToasts((prev) => [...prev, { id, message, type, duration }]);
  };

  const removeToast = (id) => {
    setToasts((prev) => prev.filter((t) => t.id !== id));
  };

  return (
    <ToastContext.Provider value={{ addToast }}>
      {children}
      <div className="fixed bottom-4 right-4 z-50 space-y-2">
        {toasts.map((toast) => (
          <ToastItem key={toast.id} {...toast} onRemove={() => removeToast(toast.id)} />
        ))}
      </div>
    </ToastContext.Provider>
  );
}

ToastProvider.propTypes = {
  children: PropTypes.node.isRequired,
};

function ToastItem({ message, type, duration, onRemove }) {
  useEffect(() => {
    const timer = setTimeout(() => {
      onRemove();
    }, duration);
    return () => clearTimeout(timer);
  }, [duration, onRemove]);

  const bgColors = {
    success: 'bg-green-500',
    error: 'bg-red-500',
    info: 'bg-blue-500',
    warning: 'bg-yellow-500',
  };

  return (
    <div className={`${bgColors[type] || 'bg-gray-800'} text-white px-4 py-2 rounded shadow-lg flex items-center justify-between min-w-[200px]`}>
      <span>{message}</span>
      <button onClick={onRemove} className="ml-4 text-white hover:text-gray-200 focus:outline-none">
        &times;
      </button>
    </div>
  );
}

ToastItem.propTypes = {
  message: PropTypes.string.isRequired,
  type: PropTypes.oneOf(['success', 'error', 'info', 'warning']),
  duration: PropTypes.number,
  onRemove: PropTypes.func.isRequired,
};
