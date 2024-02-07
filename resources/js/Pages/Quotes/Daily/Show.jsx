import DefaultLayout from "@/Layouts/DefaultLayout";
import { Head } from "@inertiajs/react";
import Quote from "@/Components/Quote";

export default function Show({ auth, quote, randomInspirationalImagePath }) {
    return (
        <DefaultLayout
            auth={auth}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Quote of the Day
                </h2>
            }
        >
            <Head title="Quote of the Day" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col lg:flex-row items-center justify-between">
                        <Quote quote={quote}></Quote>
                        <div className="lg:w-1/2 flex justify-end p-6">
                            <img
                                src={randomInspirationalImagePath}
                                alt="Inspirational"
                                className="max-w-md object-contain shadow-lg rounded-lg"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </DefaultLayout>
    );
}
