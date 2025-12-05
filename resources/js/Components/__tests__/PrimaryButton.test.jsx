import { render, screen, fireEvent } from '@testing-library/react';
import PrimaryButton from '../PrimaryButton';
import { describe, it, expect, vi } from 'vitest';

describe('PrimaryButton', () => {
    it('renders children correctly', () => {
        render(<PrimaryButton>Click Me</PrimaryButton>);
        expect(screen.getByText('Click Me')).toBeInTheDocument();
    });

    it('handles click events', () => {
        const handleClick = vi.fn();
        render(<PrimaryButton onClick={handleClick}>Click Me</PrimaryButton>);
        
        fireEvent.click(screen.getByText('Click Me'));
        expect(handleClick).toHaveBeenCalledTimes(1);
    });

    it('is disabled when disabled prop is true', () => {
        render(<PrimaryButton disabled>Click Me</PrimaryButton>);
        const button = screen.getByText('Click Me');
        expect(button).toBeDisabled();
        expect(button.classList.toString()).toContain('opacity-25');
    });
});
