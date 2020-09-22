class Filter {

    LESS_THAN = 1;
    GREATER_THAN = 2;
    EQUAL = 3;
    NOT_EQUAL = 4;
    LIKE = 5;
    BETWEEN = 6;

    values = [];

    /**
     * @return void
     */
    init() {
        let that = this;
        this.values.forEach(function (obj, index) {
            obj.operator.addEventListener('change', () => {
                that.filterOnChange(index);
            });
            obj.value.forEach(function (valueDOM, key) {
                valueDOM.addEventListener('change', () => {
                    that.filterOnChange(index);
                });
            });
        });
    }

    /**
     *
     * @param key
     */
    filterOnChange(key) {
        if (this.selectIsSet(key) && this.valuesSet(key)) {
            this.run();
        }
    }

    /**
     *
     * @param key
     * @returns {boolean}
     */
    selectIsSet(key) {
        return this.values[key].operator.value !== '';
    }

    /**
     *
     * @param key
     * @returns {boolean}
     */
    valuesSet(key) {
        if (this.values[key].operator.value != this.BETWEEN) {
            return this.values[key].value[0].value !== '';
        }

        let ret = true;
        this.values[key].value.forEach((dom, index) => {
            if (dom.value === '') {
                ret = false;
            }
        })

        return ret;
    }

    /**
     * @return void
     */
    run() {
        let form = document.getElementById('idFilterForm');
        if (!form) {
            return;
        }
        let action = form.action;
        form.action = 'run.php';
        Ajax.sendForm(form.id, this.runCallback, true);
        form.action = action;
    }

    /**
     *
     * @param {JSON} json
     */
    runCallback(json) {
        console.log(json);
    }
}