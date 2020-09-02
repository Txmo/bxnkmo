class Group {

    /**
     *
     * @param {JSON} json
     */
    static callbackForAdd(json) {
        if (!json.status || !json.data) {
            console.log('FAILED');
        }

        //id => json.data.id
        //name => json.data.name
        let nameTd = document.createElement('td');
        nameTd.classList.add('col', 's7');
        nameTd.innerText = json.data.name;


        let editLink = document.createElement('a');
        editLink.href = 'edit.php?groupId=' + json.data.id;
        editLink.classList.add('btn', 'col', 's6');
        editLink.innerText = 'Edit';

        let deleteButton = document.createElement('button');
        deleteButton.classList.add('btn', 'col', 's6');
        deleteButton.addEventListener('click', function () {
            Group.delete(json.data.id);
        });
        deleteButton.innerText = 'Delete';

        let actionTd = document.createElement('td');
        actionTd.classList.add('col', 's5', 'row');
        actionTd.appendChild(editLink);
        actionTd.appendChild(deleteButton);

        const tr = document.createElement('tr');
        tr.id = 'idGroupRow' + json.data.id;
        tr.appendChild(nameTd);
        tr.appendChild(actionTd);

        const tableBody = document.getElementById('idTableGroupBody');
        tableBody.appendChild(tr);
    }

    static delete(groupId) {
        const req = new XMLHttpRequest();
        req.responseType = "json";
        req.onreadystatechange = function () {
            if (req.readyState === 4 && req.status === 200) {
                let tr = document.getElementById('idGroupRow' + groupId);
                if (tr && req.response.status) {
                    tr.parentNode.removeChild(tr);
                }
            }
        };

        req.open('get', 'delete.php?groupId=' + groupId, true);
        req.send();
    }
}