import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, usePage } from '@inertiajs/react';

export default function ApprovalPending({ status }) {
    const { auth } = usePage().props;

    return (
        <GuestLayout>
            <Head title="Approval Pending" />

            <div className="mb-4 text-sm text-gray-600">
                Thanks for signing up! Your account is currently pending approval by the administration.
                You will be notified once your account has been reviewed and approved.
            </div>

            <div className="mt-4 flex items-center justify-between">
                <Link
                    href={route('logout')}
                    method="post"
                    as="button"
                    className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Log Out
                </Link>
            </div>
        </GuestLayout>
    );
}
