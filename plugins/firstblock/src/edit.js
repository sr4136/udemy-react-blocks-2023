import { useBlockProps } from "@wordpress/block-editor";

export default function Edit () {
    const blockProps = useBlockProps();
    return <p {...blockProps}>Edit JSX!</p>;
}