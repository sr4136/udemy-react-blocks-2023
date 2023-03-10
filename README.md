## Udemy Course
- [Gutenberg Blocks for WordPress and React Developers [2022]](https://www.udemy.com/course/gutenberg)


## General Setup

- **[LocalWP](https://localwp.com/)** to.. run WP locally. 
    - Directory: `Local Sites\udemyreactblocks2023`
    - URL: [udemyreactblocks2023.local/](udemyreactblocks2023.local)
- **GH Repo**
    - Location set to `Local Sites\udemyreactblocks2023\app\public\wp-content`
        - In order to capture changes to Plugins AND Theme modifications.

```
<details><summary></summary>


</details>
```

## Notes

### Section 1: An Introduction to the Block Editor
<details><summary>1.4 - Concepts of what belongs where for a block in terms of content, modifiers/formatting, and other settings.</summary>

**https://www.udemy.com/course/gutenberg/learn/lecture/27108642**  
- All represented content should be present in the content area. <img src="https://user-images.githubusercontent.com/4681620/184422025-27e3b68d-e8b0-41db-a093-138ae0aa3b5a.png" align="right" width="300px" />
- The toolbar is for block & element level formatting & choices.
- Sidebar is for *advanced* settings, formatting, etc-- not anything *required*.
</details>

<details><summary>1.5 - The representations of data/blocks/html.</summary>

**https://www.udemy.com/course/gutenberg/learn/lecture/27108846**
- #1 Editor Code View - the HTML that the blocks produce. **SAVE Representation** (Same ac #3)
- #2 Editor Visual View - the React Components (Complex) responsible for producing the HTMl. **EDIT Representation**
- #3 Frontend - the HTML that the blocks produce. **SAVE Representation** (Same as #1)

<img src="https://user-images.githubusercontent.com/4681620/184428005-b28c4dbb-2705-4335-b315-3c4d2fde163a.png">
    
- React component (Complex) contains lots of data: what it should look like, what the toolbar and settings should contain.
- The PLAIN HTML is what is saved to the database & what gets called with the post's content().
    - It's the React Component that takes that saved HTML and makes it editable again within its own context.
</details>

<details><summary>1.6 - How the Visual Editor is constructed from plain html.</summary>

**https://www.udemy.com/course/gutenberg/learn/lecture/27108860**
![firefox_dyIRcdjqEh](https://user-images.githubusercontent.com/4681620/184430652-62857eb8-6bf1-43ff-ad06-7919f0f86aa7.png)
</details>

<details><summary>1.7 - A Brief Introduction to the Redux-like Data Stores in the Block Editor</summary>
    
**https://www.udemy.com/course/gutenberg/learn/lecture/27108866**
Use `wp.data` to find the available data for the site.
- `wp.data.select("core/edit-post")` returns the data about the post editor.
    - Picking one of those like "isEditorSidebarOpened" can be directly ran to retrieve the exact value, as  `wp.data.select("core/edit-post").isEditorSidebarOpened()`
    - ![firefox_8eZ5rlnOhg](https://user-images.githubusercontent.com/4681620/184432136-04a894e7-6358-42b7-93d5-dc5fd45651a7.png)
- Instead of `select`, `dispatch` can be used to run actions.
    -  `wp.data.dispatch("core/edit-post").openGeneralSidebar("edit-post/block")` will open the sidebar to the Block tab.
-  `wp.data.select("core")` will contain general info: sitewide authors, taxonomies, menus, site options, etc.
-  `wp.data.select("core/blocks").getBlockTypes()` will get all registered blocks for the site.
-  `wp.data.select("core/editor")` will contain info about the currently editing post.
    - `wp.data.select("core/editor").getBlocks()` is the **Post State Array** as in [1.6 above](https://user-images.githubusercontent.com/4681620/184430652-62857eb8-6bf1-43ff-ad06-7919f0f86aa7.png)

- <img src="https://user-images.githubusercontent.com/4681620/223177938-b21cd28d-f1b1-4ac1-b746-a307b31545bf.png" align="right" width="300px" /> Since the html blocks and comments are what is used to store ALL of the info about a block-- and then parsed for React/editing: when we create blocks, we define how to store/retrieve that data. Some of it is in the attributes comment, while the rest is extracted from the html. Ex:
    - > get the image url from the `<img>` tag's `src` attribute.
    - > get the image's caption from the content of the `<figcaption>` element.
</details>

<details><summary>1.8 - The Post State Array in Action</summary>

**https://www.udemy.com/course/gutenberg/learn/lecture/27108870**

- Parse: HTML -> Blocks.
- Serialize: Blocks -> HTML.
    
- We can use take the post's stored content (`wp.data.select("core/editor").getEditedPostContent()`) and pass it into `wp.blocks.parse()` in order to generate the **Post State Array** as in [1.6 above](https://user-images.githubusercontent.com/4681620/184430652-62857eb8-6bf1-43ff-ad06-7919f0f86aa7.png).
    - This will MATCH what is produced by `wp.data.select("core/editor").getBlocks()`
    
 - Note: the word for the HTML comments that define a block are called `delimiters`   
    - Inside a delimeter lives the HTML.
    - These also contain an **object** with ID & other attributes.
    
- The PARSER is what defines how to extract the attributes from the object *AND* the HTML content. 

- `wp.blocks.serialize()` will take the Post State Array (or individual block objects) and turn them into 
    
- More from the [WP Docs](https://developer.wordpress.org/block-editor/explanations/architecture/data-flow/#serialization-and-parsing)
<img src="https://docs.google.com/drawings/d/1iuownt5etcih7rMMvPvh0Mny8zUA1Z28saxjxaWmfJ0/pub?w=1234&h=453">
</details>

---
    
### Section 2: Let's Create our First Block

<details><summary>2.10 - Registering our First Block</summary>

- Plugin / plugin-entry-point.php file.
    - `first-block/first-block.php`
    - Registers our block type using `register_block_type_from_metadata()`, which picks up on the `block.json` file we created with our block config.
    - uses `index.asset.php` which registers "wp-blocks" as an asset-- in a similar way that registering a dependancy via `wp_enqueue` works.
    - More on `register_block_type_from_metadata()` and `index.asset.php` at the WP Docs on [Dependency Management](https://developer.wordpress.org/block-editor/how-to-guides/javascript/js-build-setup/#dependency-management)
</details>

<details><summary>2. 11 - Returning React Components in the Edit & Save Functions</summary>

- [React without JSX](https://reactjs.org/docs/react-without-jsx.html)
- [WordPress Element](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-element/) provides an abstraction layer on top of React.
    - Think of it as "jQuery~ifying" React. Standard abstraction layer regardless of how React changes beneath the hood. It omits "features with uncertain futures". And also ensures compatabilities between versions.
    - 'wp-element' must be registered as a dependancy in order to use.
    - So then we can use it to create elements:
        - ```
            var createElement = wp.element.createElement;
            return createElement( "p", null, "Edit" );
          ```
</details>


<details><summary>2.12 - Compiling ESNext to ES5 with wp-scripts</summary>

- Instead of `createElement`, we're going to use ESNext and a compiler.
- We'll need to install 
- `npm init` to initialize a new `package.json`.
    - `npm install @wordpress/scripts --save-dev` for the WP Scripts.
    - `npm install @wordpress/blocks` so we can remove `var registerBlockType   = wp.blocks.registerBlockType;` and use `import { registerBlockType } from "@wordpress/blocks";` instead.
- Now, we can remove the `script.js` and `index.asset.php` in the root, as these will be auto-generated into the "build" directory upon running `npx wp-scripts build`.
    - Also update `block.json` to swap to the new `editorScript` location inside of the "build" directory.
- To watch the files for changes, instead of `npx wp-scripts build`, we can use `npx wp-scripts start`
- Edit package.json. Add to "scripts" to add shortcuts:
    - ```
      "scripts": {
        "test": "echo \"Error: no test specified\" && exit 1",
        "build": "wp-scripts build",
        "start": "wp-scripts start"
      }
      ```
    - `npx wp-scripts build` -> `npm run build`
    - `npx wp-scripts start` -> `npm run start`
- Because we use `import { registerBlockType } from "@wordpress/blocks";` within our block's `index.js`, upon inspecting `build/index.js`, it essentially comments out everything under the assumption that the global `wp` variable will be available in the browser. ~"It doesn't bundle it, but references it."
</details>


<details><summary>2.13 - Compiling & Loading SASS Files</summary>

- in `block.json`, you can pass a `style` param to load css in frontend AND editor.
    - and/or you can pass an `editorStyle` param that will load css in just the editor.
- Start getting "core" block properties: classes, attributes etc.
    - `npm install @wordpress/block-editor`
    - add `import { useBlockProps } from "@wordpress/block-editor";` to `block.json`
    - use it within the edit/save functions: `const blockProps = useBlockProps();`
        - spread the variable within the block's output: `return <p {...blockProps}>Edit (w/ JSX)</p>`
        - for the save, we only need to FRONTEND props, so `const blockProps = useBlockProps.save();`
- Add a new file in `src`, `style.scss`. wp-scripts will automatically compile this into `build/style-index.css`.
    - Edit `block.json` to include CSS:
        - `"style": "file:./build/style-index.css",` Adds styles to the frontend and backend.
- For the **Editor-only** style, add a file to `src`, `editor.scss`. wp-scripts will automatically compile this into `build/index.css`.
    - Edit `block.json` to include CSS:
        - `"editorStyle": "file:./build/index.css",` Adds styles to JUST the backend.

---

Finally, move edit/save functions & sass into respective files. Export those functions in the files. Import them in the main `index.js`. 
 - Ex at [this commit](https://github.com/sr4136/udemy-react-blocks-2022/commit/a8be723546e559de571ff49abd5fa42bc5f90aca?diff=split)
</details>

<details><summary>2.14 - Generating a Block with <em>@wordpress/create-block</em></summary>

### Major Shift
Now that we know the behind-the-scenes, we can automate most of this with `wp-create-block`.
- In the `plugins` dir, run `npx @wordpress/create-block boilerplate` 

</details>


<details><summary>2.15 - Configuring ESLint & Prettier for JavaScript Files</summary>
    
- Install WP standards for eslint/prettier. Configure them with `.eslintrc` and `package.json`.
    - `npm install @wordpress/eslint-plugin --save-dev`
    - `npm install eslint-config-prettier --save-dev`
    
</details>

<details><summary>2.18 - Adding Custom Styles to the Block Editor</summary>
    
- `add_theme_support( "editor-styles" )` along with `add_editor_style( "style-editor.css" )` (or any other stylesheet) in order to load css for ONLY the backend block editor.
    - Adding styles to the `body` tag will get auto-transformed to the block editor's wrapper, which happens to be `.editor-styles wrapper`.
        - `body { background-color: #ff0000; }` will become `.editor-styles-wrapper { background-color: #ff0000; }`
    - Additional assumptions and transforms happen, for example:
        - `.wp-block { max-width: 800px }` will become `.editor-styles-wrapper .wp-block { max-width: 800px }`
- `add_theme_support( "responsive-embeds" )` to make embeds, like YT videos.. responsive.
    
</details>
    
    
<details><summary>2.19 - Adding Support for Align Wide and Align Full</summary>
    
- `add_theme_support( "align-wide" );` and `add_theme_support( "align-full" );` to get more alignment options for blocks that support it.
- Some extra theme CSS would be required to make these look right, especially at certain breakpoints.
    
</details>
    
<details><summary>2.20 - Adding Custom Color Pallettes</summary>
    
- [`add_theme_support( "editor-color-palette" );`](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#block-color-palettes) will override the default color pallette with the supplied one.
    - There are [ways](https://wordpress.stackexchange.com/questions/357851/add-colors-to-existing-color-palette-without-replacing-it) to append to the default color palette as well. 
    - Corresponding CSS must be added for the frontend color classes.
    - To remove the "custom color" (color picker) option from the pallette, you have to ADD theme support
        - `add_theme_support( "disable-custom-colors" );`
    
- Similar overrides can be done for the gradients with modifying theme suppor for `editor-gradient-presets`.
</details>    

<details><summary>2.22 - Custom Sizing & Custom Units</summary>
    
- adding theme support for `custom-spacing` enables padding/margin controls for blocks that support it
    - ...as well as other dimensions like min-height for covor blocks.

</details>
    
---

### Section 4: Exploring Block Features by Creating a Simple Block
    
<details><summary>4.25 - Customizing the Block's Icon</summary>

- Instead of defining the block's icon with a dashicon in `block.json`, we can define it in via `index.js` as a dashicon-- as well as background/foreground colors or pass in an SVG.
    - in `registerBlockType()`:

        ```
		icon: {
		    src: 'text-page',
		    background: '#ff0000',
		    foreground: '#ffffff',
		},
        ```

        ```
		icon: (
		    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
			<circle cx="50" cy="50" r="50" />
		    </svg>
		),
        ```
    - And still can use bg/fg:

        ```
		icon: {
		    src: (
			<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
			    <circle cx="50" cy="50" r="50" />
			</svg>
		    ),
		    background: '#00ff00',
		    foreground: '#ffffff',
		},
        ```
	
</details>

	
<details><summary>4.28 - 28. The AlignmentToolbar Component</summary>
 
- Separate out the onchange functions: https://github.com/sr4136/udemy-react-blocks-simple/blob/4768e7b67343e8bc1be042bb33db7f57cb0c873a/src/edit.js#L27-L33
- Add additional classes to components using `useBlockProps`: https://github.com/sr4136/udemy-react-blocks-simple/blob/4768e7b67343e8bc1be042bb33db7f57cb0c873a/src/edit.js#L49

</details>
