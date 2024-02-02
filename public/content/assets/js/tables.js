/** @format */
/** import the simple-datatables module, implementation based on the demos/documentation from @fiduswriter/simple-datatables
 * from https://fiduswriter.github.io/simple-datatables/documentation/
 **/
import { DataTable } from "./../../libs/simple-datatables/module.js";

//get the table element if it exists in the DOM
const table = document.getElementById("dataTable");

//if the table element exists, create a new DataTable instance
if (table) {
	const dt = new DataTable("table", {
		scrollY: tableHeight,
		rowNavigation: rowNav,
		perPageSelect: pageSelect,
		classes: {
			active: "active",
			disabled: "disabled",
			selector: "form-select",
			paginationList: "pagination",
			paginationListItem: "page-item",
			paginationListItemLink: "page-link",
		},
		columns: columnArray,
		template: (options) => `<div class='${options.classes.top} '>
    ${
			options.paging && options.perPageSelect
				? `<div class='${options.classes.dropdown} bs-bars float-left'>
            <label>
                <select class='${options.classes.selector}'></select>
            </label>
        </div>`
				: ""
		}
    ${
			options.searchable
				? `<div class='${options.classes.search} float-right search btn-group'>
            <input class='${options.classes.input} form-control search-input' placeholder='Search' type='search' title='Search within table'>
        </div>`
				: ""
		}
</div>
<div class='${options.classes.container}'${
			options.scrollY.length
				? ` style='height: ${options.scrollY}; overflow-Y: auto;'`
				: ""
		}></div>
<div class='${options.classes.bottom} '>
    ${options.paging ? `<div class='${options.classes.info}'></div>` : ""}
    <nav class='${options.classes.pagination}'></nav>
</div>`,
		tableRender: (_data, table, _type) => {
			const thead = table.childNodes[0];
			thead.childNodes[0].childNodes.forEach((th) => {
				//if the th is not sortable, don't add the sortable class
				if (th.options?.sortable === false) {
					return;
				} else {
					if (!th.attributes) {
						th.attributes = {};
					}
					th.attributes.scope = "col";
					const innerHeader = th.childNodes[0];
					if (!innerHeader.attributes) {
						innerHeader.attributes = {};
					}
					let innerHeaderClass = innerHeader.attributes.class
						? `${innerHeader.attributes.class} th-inner`
						: "th-inner";

					if (innerHeader.nodeName === "a") {
						innerHeaderClass += " sortable sortable-center both";
						if (th.attributes.class?.includes("desc")) {
							innerHeaderClass += " desc";
						} else if (th.attributes.class?.includes("asc")) {
							innerHeaderClass += " asc";
						}
					}
					innerHeader.attributes.class = innerHeaderClass;
				}
			});

			return table;
		},
	});
	dt.columns.add({
		data: dt.data.data.map((_row, index) => index),
		heading: "#",
		render: (_data, td, _index, _cIndex) => {
			if (!td.attributes) {
				td.attributes = {};
			}
			td.attributes.scope = "row";
			td.nodeName = "TH";
			return td;
		},
	});
	dt.columns.order(columnOrder);
	window.dt = dt;
}
