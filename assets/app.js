import './styles/app.scss';

import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import Table from '@editorjs/table';
import Code from '@editorjs/code';

import {Dropdown} from "bootstrap";

document.addEventListener('DOMContentLoaded', async () => {
    const app = new App();
    app.initEditor();
});

class App {

    /** @type {EditorJS}*/
    editor;
    constructor() {
        this.enableDropdown();
    }

    enableDropdown() {
        const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
        const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl));
    }

    initEditor() {
        this.editor = new EditorJS({
            holder: 'editor',
            logLevel: 'ERROR',
            tools: {
                header: Header,
                table: Table,
                code: Code,
                list: List
            }
        });
    }
}