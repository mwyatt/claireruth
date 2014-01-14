var config = {
        site: 'claire-ruth',
        content: '',
        documentBody: '',
        url: {
                base: '/',
                admin: '/',
                adminAjax: '/',
                ajax: '/',
        },
        spinner: '<div class="spinner is-tall"></div>',
        setup: function() {
                config.content = $('.content');
                config.documentBody = $(document.body);
                config.url.base = $('body').data('url-base');
                config.url.ajax = config.url.base + 'ajax/'
                config.url.admin = config.url.base + 'admin/';
                config.url.adminAjax = config.url.admin + 'ajax/';
                config.url.currentNoQuery = [location.protocol, '//', location.host, location.pathname].join('');
        },
};
$.ajaxSetup ({  
        cache: false  
});
