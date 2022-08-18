import AppForm from '../app-components/Form/AppForm';

Vue.component('page-setting-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                business_id:  '' ,
                tag_name:  '' ,
                block1:  '' ,
                block2:  '' ,
                block3:  '' ,
                status:  '' ,
                
            }
        }
    }

});