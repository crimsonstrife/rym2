/** @format */

//initialize the wysiwyg editors
document.querySelectorAll(".wysiwyg-editor").forEach((e) => {
	ClassicEditor.create(e, {
		removePlugins: [
			"Image",
			"EasyImage",
			"ImageCaption",
			"ImageStyle",
			"ImageToolbar",
			"ImageUpload",
			"MediaEmbed",
			"CKFinder",
			"CKFinderUploadAdapter",
			"LinkImage",
			"ImageInsert",
			"ImageResize",
		],
		toolbar: {
			items: [
				"heading",
				"|",
				"bold",
				"italic",
				"|",
				"link",
				"bulletedList",
				"numberedList",
				"|",
				"outdent",
				"indent",
				"|",
				"blockQuote",
				"insertTable",
				"undo",
				"redo",
			],
		},
	})
		.then((editor) => {
			console.log("Editor Initialized", editor);
			editor.model.document.on("change:data", () => {
				e.value = editor.getData();
			});
		})
		.catch((error) => {
			console.error(error);
		});
});
