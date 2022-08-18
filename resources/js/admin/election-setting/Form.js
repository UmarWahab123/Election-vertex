import AppForm from '../app-components/Form/AppForm';

Vue.component('election-setting-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                meta_key:  '' ,
                meta_value:  '' ,
                
            }
        }
    }

});