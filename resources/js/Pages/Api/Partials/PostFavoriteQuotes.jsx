import React, { useState } from "react";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import PrimaryButton from "@/Components/PrimaryButton";
import { useApi } from "@/Contexts/ApiContext";

export default function PostFavoriteQuotes({ auth, currentApiToken }) {
    const { favoriteQuoteService } = useApi();
    
    const [apiToken, setApiToken] = useState(currentApiToken);

    const [quoteId, setQuoteId] = useState(1);
    const [action, setAction] = useState("add");
    const [buttonDisabled, setButtonDisabled] = useState(false);

    const [apiResponse, setApiResponse] = useState("");

    const handleClick = async () => {
        setButtonDisabled(true);

        favoriteQuoteService.setApiToken(apiToken);

        let data = "[]";
        try {
            if (action === "add") {
                data = await favoriteQuoteService.add(quoteId);
            } else if (action === "remove") {
                data = await favoriteQuoteService.remove(quoteId);
            }
            setApiResponse(JSON.stringify(data, null, 2));
        } catch (error) {
            if (error.response) {
                setApiResponse(JSON.stringify(error.response, null, 2));
            } else {
                setApiResponse('Failed to fetch data.');
            }
        } finally {
            setButtonDisabled(false);
        }

        setButtonDisabled(false);
    };

    return (
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div className="p-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div className="text-gray-900">
                    <h2>POST /api/favorite-quotes</h2>
                </div>
                {auth.user && (
                    <div className="text-gray-900 mt-6 max-w-xl">
                        <InputLabel htmlFor="apiToken" value="API Token" />

                        <TextInput
                            id="apiToken"
                            type="text"
                            className="mt-1 block w-full"
                            value={apiToken}
                            onChange={(e) => setApiToken(e.target.value)}
                            required
                        />
                    </div>
                )}
                <div className="text-gray-900 mt-6">
                    <InputLabel htmlFor="quoteId" value="Quote ID" />

                    <TextInput
                        id="quoteId"
                        type="text"
                        className="mt-1 block"
                        value={quoteId}
                        onChange={(e) => setQuoteId(e.target.value)}
                        required
                    />
                </div>
                <div className="text-gray-900 mt-6">
                    <InputLabel htmlFor="action" value="Action" />
                    <select
                        id="action"
                        value={action}
                        onChange={(e) => setAction(e.target.value)}
                        className="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    >
                        <option value="add">Add</option>
                        <option value="remove">Remove</option>
                    </select>
                </div>
                <div className="mt-6">
                    <PrimaryButton
                        onClick={() => handleClick()}
                        disabled={buttonDisabled}
                    >
                        {buttonDisabled ? "Loading" : "Send"}
                    </PrimaryButton>
                </div>
                <div className="mt-6">
                    <InputLabel value="API Response" />
                    <pre className="mt-1 bg-gray-100 rounded p-3 overflow-x-auto min-h-[80px]">
                        {apiResponse}
                    </pre>
                </div>
            </div>
        </div>
    );
}
