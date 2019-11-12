import Table from "./components/Table/Table.js";
import ImportOptions from "./components/ImportOptions/ImportOptions";

const routes = [
    {
        path: "/",
        component: Table,
        exact: true,
        text: "Home"
    },
    {
        path: "/import",
        component: ImportOptions,
        exact: true,
        text: "Import"
    }
];

export default routes;
