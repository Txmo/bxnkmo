class Helper {

    static INFO_TYPE_WARN = 1;
    static INFO_TYPE_ERROR = 2;
    static INFO_TYPE_SUCCESS = 3;

    /**
     * Toggles element with given id
     * @param {string} id
     */
    static toggle(id) {
        const elem = document.getElementById(id);
        if (!elem) {
            return;
        }
        if (elem.classList.contains('hide')) {
            elem.classList.remove('hide');
        } else {
            elem.classList.add('hide');
        }
    }

    static remove(id) {
        let dom = document.getElementById(id);
        if (dom) {
            dom.parentNode.removeChild(dom);
        }
    }

    /**
     * Shows the given message
     * @param {string} message
     * @param {number} type
     */
    static info(message, type = Helper.INFO_TYPE_WARN) {

        const infoId = 'idInfoOuterDom';
        Helper.remove(infoId);


    }
}