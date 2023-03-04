<script>
    if (localStorage.getItem('font') == null) {
        localStorage.setItem('font', 1);
    } else {
        document.getElementById('font_size').value = localStorage.getItem('font');
    }
    if (document.querySelector('.itemInfoTable thead th') != null) {
        var base_thead = Number(window.getComputedStyle(document.querySelector('.itemInfoTable thead th'))['fontSize'].replace('px', ''));
        set_font_size(localStorage.getItem('font'), base_thead, 'head')

    }
    if (document.querySelector('.itemInfoTable tbody td') != null) {
        var base_tbody = Number(window.getComputedStyle(document.querySelector('.itemInfoTable tbody td'))['fontSize'].replace('px', ''));
        set_font_size(localStorage.getItem('font'), base_tbody, 'body')

    }
    if (document.getElementById('tableDiv').style.fontSize != null) {
        var base_table = document.getElementById('tableDiv').style.fontSize.replace('px', '')
        set_font_size(localStorage.getItem('font'), base_table, 'table')
    }


    function set_font_size(mult, base, place) {
        console.log(place)
        switch (place) {
            case 'head':
                document.querySelectorAll('.itemInfoTable thead th').forEach((el) => {
                    el.style.fontSize = base * mult + 'px';
                })
                break;
            case 'body' :
                document.querySelectorAll('.itemInfoTable tbody td').forEach((el) => {
                    el.style.fontSize = base * mult + 'px';
                })
                break;
            case 'table':
                document.getElementById('tableDiv').style.fontSize = base * mult + 'px';
                break;
        }


    }

    function save_font_size() {
        localStorage.setItem('font', document.getElementById('font_size').value)
        if (document.querySelector('.itemInfoTable thead th') != null) {
            set_font_size(localStorage.getItem('font'), window.base_thead, 'head')
        }
        if (document.querySelector('.itemInfoTable tbody td') != null) {
            set_font_size(localStorage.getItem('font'), window.base_tbody, 'body')

        }
        if (document.getElementById('tableDiv').style.fontSize != null) {
            set_font_size(localStorage.getItem('font'), window.base_table, 'table')
        }
        try {
            get_table_data()
        } catch (e) {

        }
        try {
            getTableData()
        } catch (e) {

        }
        try {
            get_table(document.getElementById('year').value)
        } catch (e) {

        }
    }
</script>
