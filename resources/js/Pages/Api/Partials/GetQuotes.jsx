import React, { useState } from "react";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import PrimaryButton from "@/Components/PrimaryButton";
import { useApi } from "@/Contexts/ApiContext";

export default function GetQuotes({ auth, currentApiToken }) {
    const { randomQuoteService } = useApi();

    const [apiToken, setApiToken] = useState(currentApiToken);

    const [forceReload, setForceReload] = useState(false);
    const [buttonDisabled, setButtonDisabled] = useState(false);

    const [apiResponse, setApiResponse] = useState("");

    const handleClick = async () => {
        setButtonDisabled(true);

        randomQuoteService.setApiToken(apiToken);

        try {
            let data = await randomQuoteService.index(forceReload);
            setApiResponse(JSON.stringify(data, null, 2));
        } catch (error) {
            if (error.response) {
                setApiResponse(JSON.stringify(error.response, null, 2));
            } else {
                setApiResponse("Failed to fetch data.");
            }
        } finally {
            setButtonDisabled(false);
        }
    };

    return (
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div className="p-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div className="text-blue-600">
                    <h2>
                        <a
                            href={`/api/quotes${forceReload ? "/new" : ""}`}
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            GET /api/quotes
                            {forceReload ? "/new" : ""}
                        </a>
                    </h2>
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
                <div className="flex items-center mt-6">
                    <input
                        id="includeNew"
                        type="checkbox"
                        checked={forceReload}
                        onChange={(e) => setForceReload(e.target.checked)}
                        className="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    />
                    <label
                        htmlFor="includeNew"
                        className="ml-2 text-sm font-medium text-gray-900"
                    >
                        Include "new" parameter
                    </label>
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
