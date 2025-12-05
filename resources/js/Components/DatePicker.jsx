import React from 'react';
import PropTypes from 'prop-types';

/**
 * Reusable date picker component.
 * Props:
 *  - id: string (required)
 *  - label: string
 *  - value: string (YYYY-MM-DD)
 *  - onChange: function(event)
 *  - min: string (optional min date)
 *  - max: string (optional max date)
 *  - error: string (error message)
 *  - className: string (additional Tailwind classes)
 */
export default function DatePicker({ id, label, value, onChange, min, max, error, className = '' }) {
  return (
    <div className={`mb-4 ${className}`}>
      {label && (
        <label htmlFor={id} className="block text-sm font-medium text-gray-700 mb-1">
          {label}
        </label>
      )}
      <input
        id={id}
        type="date"
        value={value}
        onChange={onChange}
        min={min}
        max={max}
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

DatePicker.propTypes = {
  id: PropTypes.string.isRequired,
  label: PropTypes.string,
  value: PropTypes.string,
  onChange: PropTypes.func,
  min: PropTypes.string,
  max: PropTypes.string,
  error: PropTypes.string,
  className: PropTypes.string,
};
