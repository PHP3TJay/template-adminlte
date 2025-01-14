<style>
    .sortable-container {
        margin: 0 auto;
        padding: 0 20px;
        width: 100%;
    }
    .connected-sortable {
        margin: 0 auto;
        list-style: none;
        width: 100%;
    }

    li.draggable-item {
        width: inherit;
        padding: 15px 20px;
        background-color: #ffffff;
        -webkit-transition: transform .25s ease-in-out;
        -moz-transition: transform .25s ease-in-out;
        -o-transition: transform .25s ease-in-out;
        transition: transform .25s ease-in-out;
        
        -webkit-transition: box-shadow .25s ease-in-out;
        -moz-transition: box-shadow .25s ease-in-out;
        -o-transition: box-shadow .25s ease-in-out;
        transition: box-shadow .25s ease-in-out;
        &:hover {
            cursor: pointer;
            background-color: #eaeaea;
        }
    }
        /* styles during drag */
    li.draggable-item.ui-sortable-helper {
        background-color: #ffffff;
        -webkit-box-shadow: 0 0 8px rgba(53,41,41, .8);
        -moz-box-shadow: 0 0 8px rgba(53,41,41, .8);
        box-shadow: 0 0 8px rgba(53,41,41, .8);
        transform: scale(1.015);
        z-index: 100;
    }
    li.draggable-item.ui-sortable-placeholder {
        background-color: #ffffff;
        -moz-box-shadow:    inset 0 0 10px #616060;
        -webkit-box-shadow: inset 0 0 10px #000000;
        box-shadow:         inset 0 0 10px #000000;
    }
</style>