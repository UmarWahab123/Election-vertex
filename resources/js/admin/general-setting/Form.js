import AppForm from '../app-components/Form/AppForm';

Vue.component('general-setting-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                business_id:  '' ,
                general_tag:  '' ,
                status:  '' ,
                
            }
        }
    }

});