const UserSettings = {

    el: '#settings',

    data: window.$data,

    methods: {
        save() {
            this.$http.post('admin/system/settings/config', { name: 'system/user', config: this.config }).then(function () {
                        this.$notify('Settings saved.');
                    }, function (res) {
                        this.$notify(res.data, 'danger');
                    });
        }
    }
};

Vue.ready(UserSettings);
