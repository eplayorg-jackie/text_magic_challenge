import React, { Component } from 'react';
import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";

import Challenges from "./components/Challenges";

class Handler extends Component {
    render() {
        <Router>
            <Routes>
                <Route path="/" element={<Challenges/>}/>
            </Routes>
        </Router>
    };
}

export default Handler;

const rootElement = document.getElementById("app");
const root = createRoot(rootElement);

root.render(
    <StrictMode>
        <Handler/>
    </StrictMode>
);
