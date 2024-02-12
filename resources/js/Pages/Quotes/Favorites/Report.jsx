import QuoteSection from "@/Components/QuoteSection";
import DefaultLayout from "@/Layouts/DefaultLayout";
import { Head } from "@inertiajs/react";
import { Link } from "@inertiajs/react";

export default function Report({ auth, users }) {
    return (
        <DefaultLayout
            auth={auth}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Report of Favorite Quotes
                </h2>
            }
        >
            <Head title="Report of Favorite Quotes" />
            <div className="py-12">
                {users.map((user) => (
                    <div
                        key={user.id}
                        className="mb-5 max-w-7xl mx-auto sm:px-6 lg:px-8"
                    >
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6 text-gray-900">
                                <h2 className={`${user.favorite_quotes.length > 0 ? "mb-4" : ""} text-lg font-medium text-gray-900 text-center`}>
                                    <Link
                                        href={`${route("login")}?username=${encodeURIComponent(user.username)}`}
                                        className="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm"
                                    >
                                        {user.username}
                                    </Link>
                                </h2>
                                {user.favorite_quotes.map((quote) => (
                                    <div key={quote.id}>
                                        <hr />
                                        <QuoteSection
                                            auth={
                                                user.id === auth.user.id
                                                    ? auth
                                                    : null
                                            }
                                            quote={quote}
                                            isFavorite={true}
                                        />
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </DefaultLayout>
    );
}
