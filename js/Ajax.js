class Ajax {
    
    /**
     * @param {string} formId
     * @param {function} callback
     * @param {boolean} isJSONResponse
     */
    static sendForm(formId, callback, isJSONResponse) {
        let form = document.getElementById(formId);
        if (!form || !form.getAttribute('action') || !form.getAttribute('method')) {
            Ajax.invalidForm();
            return;
        }
        const request = new XMLHttpRequest();
        if (isJSONResponse === undefined || isJSONResponse) {
            request.responseType = "json";
        }
        request.onreadystatechange = function () {
            if (request.readyState === 4 && request.status === 200) {
                if (typeof callback === 'function') {
                    callback(request.response);
                }
            }
        };
        if (form.getAttribute('method').toLowerCase() === 'post') {
            Ajax.postForm(form, request);
        } else {
            Ajax.getForm(form, request);
        }
    }

    /**
     * @param {HTMLFormElement} form
     * @param {XMLHttpRequest} request
     */
    static postForm(form, request) {
        request.open('post', form.getAttribute('action'));
        const formData = new FormData(form);
        let input;
        for (let i = 0; i < form.elements.length; i++) {
            input = form.elements[i];
            if (!input.hasAttribute('name') || input.nodeName.toUpperCase() !== "INPUT") {
                continue;
            }
            if (input.getAttribute('type').toUpperCase() === 'SUBMIT') {
                formData.append(input.getAttribute('name'), input.getAttribute('value'));
            }
        }
        request.send(formData);
    }

    /**
     * @param {HTMLFormElement} form
     * @param {XMLHttpRequest} request
     */
    static getForm(form, request) {
        let oField, sFieldType, nFile, sSearch = "";
        for (let nItem = 0; nItem < form.elements.length; nItem++) {
            oField = form.elements[nItem];
            if (!oField.hasAttribute("name")) {
                continue;
            }
            sFieldType = oField.nodeName.toUpperCase() === "INPUT" ?
                oField.getAttribute("type").toUpperCase() : "TEXT";
            if (sFieldType === "FILE") {
                for (nFile = 0; nFile < oField.files.length;) {
                    sSearch += "&" + escape(oField.name) + "=" + escape(oField.files[nFile++].name);
                }
            } else if ((sFieldType !== "RADIO" && sFieldType !== "CHECKBOX") || oField.checked) {
                sSearch += "&" + escape(oField.name) + "=" + escape(oField.value);
            }
        }
        request.open("get", form.getAttribute('action').replace(/(?:\?.*)?$/, sSearch.replace(/^&/, "?")), true);
        request.send(null);
    }

    static invalidForm() {
        console.log('invalid Form');
    }
}