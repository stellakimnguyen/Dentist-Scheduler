function sendQuery() {
    var query = document.getElementById("query").value;
    document.cookie = `DBAquery=${JSON.stringify(query)}`;
}