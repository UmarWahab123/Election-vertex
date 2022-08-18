import AppForm from '../app-components/Form/AppForm';

Vue.component('business-account-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                business_id:  '' ,
                ref_id:  '' ,
                credit:  '' ,
                details:  '' ,
                debit:  '' ,
                balance:  '' ,
                img_url:  '' ,
                status:  '' ,
                meta:  '' ,

            }
        }
    }

});
