import AppForm from '../app-components/Form/AppForm';

Vue.component('data-set-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                phone:  '' ,
                address:  '' ,
                tag:  '' ,
                meta:  '' ,
                status:  '' ,
                
            }
        }
    }

});