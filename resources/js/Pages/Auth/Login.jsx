import { useEffect } from "react";
import DefaultLayout from "@/Layouts/DefaultLayout";
import GuestLayout from "@/Layouts/GuestLayout";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Head, Link, useForm } from "@inertiajs/react";

export default function Login({ auth, authenticatedUsers, username }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        username: username || "",
        password: "",
    });

    useEffect(() => {
        return () => {
            reset("password");
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();

        post(route("login"));
    };

    const formContent = (
        <>
            {" "}
            <Head title="Log in" />
            <form onSubmit={submit}>
                <div>
                    <InputLabel htmlFor="username" value="Username" />

                    <TextInput
                        id="username"
                        type="text"
                        name="username"
                        value={data.username}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused={true}
                        onChange={(e) => setData("username", e.target.value)}
                    />

                    <InputError message={errors.username} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="current-password"
                        onChange={(e) => setData("password", e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="flex items-center justify-end mt-4">
                    {!auth.user && (
                        <Link
                            href={route("register")}
                            className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Don't have an account yet?
                        </Link>
                    )}
                    <PrimaryButton className="ms-4" disabled={processing}>
                        Log in
                    </PrimaryButton>
                </div>
            </form>
        </>
    );

    if (auth.user) {
        return (
            <DefaultLayout
                auth={auth}
                header={
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        Log in
                    </h2>
                }
            >
                <Head title="Log in" />

                <div className="py-12">
                    <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                        <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            {formContent}
                        </div>
                        {Object.keys(authenticatedUsers).length > 1 && (
                            <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <section className={`space-y-6 max-w-xl`}>
                                    <header>
                                        <h2 className="text-lg font-medium text-gray-900">
                                            Switch Account
                                        </h2>
                                    </header>
                                    <ul>
                                        {Object.values(authenticatedUsers)
                                            .filter(
                                                (username) =>
                                                    username !=
                                                    auth.user.username
                                            )
                                            .map((username, index) => (
                                                <li
                                                    key={index}
                                                    className="py-2"
                                                >
                                                    <a
                                                        href="#"
                                                        onClick={(e) => {
                                                            e.preventDefault();
                                                            setData(
                                                                (
                                                                    prevState
                                                                ) => ({
                                                                    ...prevState,
                                                                    username:
                                                                        username,
                                                                })
                                                            );
                                                        }}
                                                        className="text-blue-600 hover:text-blue-800 visited:text-purple-600"
                                                    >
                                                        {username}
                                                    </a>
                                                </li>
                                            ))}
                                    </ul>
                                </section>
                            </div>
                        )}
                    </div>
                </div>
            </DefaultLayout>
        );
    } else {
        return <GuestLayout>{formContent}</GuestLayout>;
    }
}
