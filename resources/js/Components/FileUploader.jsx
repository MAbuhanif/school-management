import React, { useRef, useState } from 'react';
import PropTypes from 'prop-types';

export default function FileUploader({ id, label, multiple = false, accept, onChange, error, className = '' }) {
  const fileInputRef = useRef(null);
  const [fileNames, setFileNames] = useState([]);

  const handleFileChange = (e) => {
    const files = Array.from(e.target.files);
    setFileNames(files.map(f => f.name));
    if (onChange) {
      onChange(files);
    }
  };

  const handleClick = () => {
    fileInputRef.current.click();
  };

  const handleDrop = (e) => {
    e.preventDefault();
    e.stopPropagation();
    const files = Array.from(e.dataTransfer.files);
    if (files && files.length > 0) {
        setFileNames(files.map(f => f.name));
        if (onChange) {
            onChange(files);
        }
    }
  };

  const handleDragOver = (e) => {
    e.preventDefault();
    e.stopPropagation();
  };

  return (
    <div className={`mb-4 ${className}`}>
      {label && (
        <label htmlFor={id} className="block text-sm font-medium text-gray-700 mb-1">
          {label}
        </label>
      )}
      <div
        className={`mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-indigo-500 ${error ? 'border-red-500' : ''}`}
        onClick={handleClick}
        onDrop={handleDrop}
        onDragOver={handleDragOver}
      >
        <div className="space-y-1 text-center">
          <svg
            className="mx-auto h-12 w-12 text-gray-400"
            stroke="currentColor"
            fill="none"
            viewBox="0 0 48 48"
            aria-hidden="true"
          >
            <path
              d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
              strokeWidth={2}
              strokeLinecap="round"
              strokeLinejoin="round"
            />
          </svg>
          <div className="flex text-sm text-gray-600">
            <span className="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
              Upload a file
            </span>
            <p className="pl-1">or drag and drop</p>
          </div>
          <p className="text-xs text-gray-500">
            {accept ? accept.replace(/,/g, ', ') : 'Any file'} up to 10MB
          </p>
          {fileNames.length > 0 && (
            <div className="mt-2 text-sm text-gray-700">
              Selected: {fileNames.join(', ')}
            </div>
          )}
        </div>
        <input
          id={id}
          ref={fileInputRef}
          type="file"
          className="sr-only"
          multiple={multiple}
          accept={accept}
          onChange={handleFileChange}
        />
      </div>
      {error && (
        <p className="mt-2 text-sm text-red-600" id={`${id}-error`}>
          {error}
        </p>
      )}
    </div>
  );
}

FileUploader.propTypes = {
  id: PropTypes.string.isRequired,
  label: PropTypes.string,
  multiple: PropTypes.bool,
  accept: PropTypes.string,
  onChange: PropTypes.func,
  error: PropTypes.string,
  className: PropTypes.string,
};
