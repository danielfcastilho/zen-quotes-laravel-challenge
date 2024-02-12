import DefaultLayout from "@/Layouts/DefaultLayout";
import { Head } from "@inertiajs/react";

export default function List({ auth, quotes }) {
    console.log(quotes);
    return (
        <DefaultLayout
            auth={auth}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Five Random Quotes
                </h2>
            }
        >
            <Head title="Five Random Quotes" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900"></div>
                    </div>
                </div>
            </div>
        </DefaultLayout>
    );
}
