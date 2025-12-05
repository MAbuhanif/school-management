import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm, usePage } from '@inertiajs/react';
import { useState } from 'react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import DangerButton from '@/Components/DangerButton';

export default function TwoFactor({ secret, qrCodeUrl }) {
    const user = usePage().props.auth.user;
    const [enabled, setEnabled] = useState(!!user.google2fa_secret);

    const { data, setData, post, processing, errors, reset } = useForm({
        secret: secret,
        code: '',
    });

    const { delete: destroy, processing: disabling } = useForm({
        password: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('two-factor.store'), {
            onSuccess: () => {
                setEnabled(true);
                reset('code');
            },
        });
    };

    const disable = (e) => {
        e.preventDefault();
        // For simplicity, we assume password confirmation is handled via a modal or separate simple confirm
        // But for now, let's just ask for confirmation or assume current session is enough (Controller asks for password)
        // Controller validation: 'password' => 'required|current_password'
        // So we need to prompt for password.
        const password = prompt("Please enter your password to disable 2FA:");
        if (password) {
            destroy(route('two-factor.disable', { password: password }), {
                onSuccess: () => setEnabled(false),
            });
        }
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Two-Factor Authentication Setup</h2>}
        >
            <Head title="Two-Factor Authentication" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            {enabled ? (
                                <section className="space-y-6">
                                    <header>
                                        <h2 className="text-lg font-medium text-gray-900">Two Factor Authentication</h2>
                                        <p className="mt-1 text-sm text-gray-600">
                                            Two-factor authentication is enabled.
                                        </p>
                                    </header>
                                    <div className="flex items-center gap-4">
                                        <DangerButton disabled={disabling} onClick={disable}>
                                            Disable 2FA
                                        </DangerButton>
                                    </div>
                                </section>
                            ) : (
                                <section className="space-y-6">
                                    <header>
                                        <h2 className="text-lg font-medium text-gray-900">Two Factor Authentication</h2>
                                        <p className="mt-1 text-sm text-gray-600">
                                            Add additional security to your account using two-factor authentication.
                                        </p>
                                    </header>

                                    <div>
                                        <div className="mb-4" dangerouslySetInnerHTML={{ __html: qrCodeUrl }} />
                                        
                                        <p className="mb-4 text-sm text-gray-600 font-mono break-all">
                                            Secret: {secret}
                                        </p>

                                        <form onSubmit={submit} className="mt-6 space-y-6">
                                            <div>
                                                <InputLabel htmlFor="code" value="Authentication Code" />

                                                <TextInput
                                                    id="code"
                                                    className="mt-1 block w-full"
                                                    value={data.code}
                                                    onChange={(e) => setData('code', e.target.value)}
                                                    required
                                                    isFocused
                                                    autoComplete="one-time-code"
                                                />

                                                <InputError className="mt-2" message={errors.code} />
                                            </div>

                                            <div className="flex items-center gap-4">
                                                <PrimaryButton disabled={processing}>Enable</PrimaryButton>
                                            </div>
                                        </form>
                                    </div>
                                </section>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
