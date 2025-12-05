import React from 'react';
import PropTypes from 'prop-types';

/**
 * Reusable input component with label and error message.
 * Props:
 *  - id: string (required)
 *  - label: string
 *  - type: string (default 'text')
 *  - value: string
 *  - onChange: function
 *  - placeholder: string
 *  - disabled: boolean
 *  - error: string (error message)
 *  - className: string (additional Tailwind classes)
 */
export default function Input({ id, label, type = 'text', value, onChange, placeholder, disabled = false, error, className = '' }) {
  return (
    <div className={`mb-4 ${className}`}>
      {label && (
        <label htmlFor={id} className="block text-sm font-medium text-gray-700 mb-1">
          {label}
        </label>
      )}
      <input
        id={id}
        type={type}
        value={value}
        onChange={onChange}
        placeholder={placeholder}
        disabled={disabled}
        className={`mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 ${error ? 'border-red-500' : ''}`}
        aria-invalid={error ? 'true' : 'false'}
        aria-describedby={error ? `${id}-error` : undefined}
      />
      {error && (
        <p className="mt-2 text-sm text-red-600" id={`${id}-error`}>
          {error}
        </p>
      )}
    </div>
  );
}

Input.propTypes = {
  id: PropTypes.string.isRequired,
  label: PropTypes.string,
  type: PropTypes.string,
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  onChange: PropTypes.func,
  placeholder: PropTypes.string,
  disabled: PropTypes.bool,
  error: PropTypes.string,
  className: PropTypes.string,
};
