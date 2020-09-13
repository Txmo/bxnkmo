class OperatorHelper {

    select;
    mainInputContainer;
    andContainer;
    betweenContainer;

    constructor(obj) {
        this.select = obj.selectDOM;
        this.mainInputContainer = obj.mainDOM;
        this.andContainer = obj.andDOM;
        this.betweenContainer = obj.betweenDOM;
        let that = this;
        this.select.addEventListener('change', function () {
            OperatorHelper.selectChange(that);
        });
        OperatorHelper.selectChange(that);
    }

    static selectChange(helper) {
        if (helper.select.options[helper.select.selectedIndex].value == 6) {
            helper.mainInputContainer.classList.remove('s8');
            helper.mainInputContainer.classList.add('s3');
            helper.andContainer.classList.remove('hide');
            helper.betweenContainer.classList.remove('hide');
        } else {
            helper.mainInputContainer.classList.remove('s3');
            helper.mainInputContainer.classList.add('s8');
            helper.andContainer.classList.add('hide');
            helper.betweenContainer.classList.add('hide');
        }
    }


}