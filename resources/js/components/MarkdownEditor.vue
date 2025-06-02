<script setup lang="ts">

import { Editor, EditorContent, useEditor } from '@tiptap/vue-3';
import { StarterKit } from '@tiptap/starter-kit';
import { Link } from '@tiptap/extension-link';
import { onMounted, watch } from 'vue';
import { Markdown } from 'tiptap-markdown';
import 'remixicon/fonts/remixicon.css';
import { Placeholder } from '@tiptap/extension-placeholder';

const props = defineProps({
    editorClass: '',
    placeholder: null
});

const model = defineModel();

const editor = useEditor({
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [1, 2, 3]
            },
            code: false,
            codeBlock: false
        }),
        Link,
        Markdown,
        Placeholder.configure({
            placeholder: props.placeholder
        })
    ],
    editorProps: {
        attributes: {
            class: `prose prose-sm max-w-none text-white ${props.editorClass ? props.editorClass : 'min-h-[200px]'}`
        }
    },
    onUpdate: () => model.value = editor.value?.storage.markdown.getMarkdown()
}) as Editor;

defineExpose({focus: () => editor.value?.commands.focus()});


onMounted(() => {
    watch(model, (value) => {
        if (value === editor.value?.storage.markdown.getMarkdown()) {
            return;
        }

        editor.value?.commands.setContent(value);
    }, { immediate: true });
})

const promptUserForHref = () => {
    if (editor.value?.isActive('link')) {
        return editor.value?.chain().unsetLink().run();
    }

    const href = prompt('Where do you want to link to?');

    if (!href) {
        return editor.value?.chain().focus().run();
    }

    return editor.value?.chain().focus().setLink({ href }).run();
};
</script>

<template>
    <div
        class="markdown placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30
        border-input min-w-0 rounded-md border bg-transparent text-base shadow-xs transition-[color,box-shadow] outline-none
        disabled:pointer-events-none
        disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]
        aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive w-full">
        <menu class="flex divide-x border-b">
            <li>
                <button @click="() => editor.chain().focus().toggleBold().run()"
                        type="button"
                        title="Bold"
                        class="px-3 py-2 shadow-xs  transition-all rounded-tl-md"
                        :class="[editor?.isActive('bold') ? 'bg-indigo-500' : 'hover:bg-primary hover:text-primary-foreground hover:bg-primary/90']">
                    <i class="ri-bold"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleItalic().run()"
                        type="button"
                        title="Italic"
                        class="px-3 py-2 shadow-xs transition-all"
                        :class="[editor?.isActive('italic') ? 'bg-indigo-500' : 'hover:bg-primary hover:text-primary-foreground hover:bg-primary/90']">
                    <i class="ri-italic"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleStrike().run()"
                        type="button"
                        title="Strikethrough"
                        class="px-3 py-2 shadow-xs transition-all"
                        :class="[editor?.isActive('strike') ? 'bg-indigo-500' : 'hover:bg-primary hover:text-primary-foreground hover:bg-primary/90']">
                    <i class="ri-strikethrough"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleBlockquote().run()"
                        type="button"
                        title="Blockquote"
                        class="px-3 py-2 shadow-xs transition-all"
                        :class="[editor?.isActive('blockquote') ? 'bg-indigo-500' : 'hover:bg-primary hover:text-primary-foreground hover:bg-primary/90']">
                    <i class="ri-double-quotes-l"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleBulletList().run()"
                        type="button"
                        title="Bullet list"
                        class="px-3 py-2 shadow-xs transition-all"
                        :class="[editor?.isActive('bulletList') ? 'bg-indigo-500' : 'hover:bg-primary hover:text-primary-foreground hover:bg-primary/90']">
                    <i class="ri-list-unordered"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleOrderedList().run()"
                        type="button"
                        title="Numeric list"
                        class="px-3 py-2 shadow-xs transition-all"
                        :class="[editor?.isActive('orderedList') ? 'bg-indigo-500' : 'hover:bg-primary hover:text-primary-foreground hover:bg-primary/90']">
                    <i class="ri-list-ordered"></i>
                </button>
            </li>
            <li>
                <button @click="promptUserForHref"
                        type="button"
                        title="Add link"
                        class="px-3 py-2 shadow-xs transition-all"
                        :class="[editor?.isActive('link') ? 'bg-indigo-500' : 'hover:bg-primary hover:text-primary-foreground hover:bg-primary/90']">
                    <i class="ri-link"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleHeading({level: 1}).run()"
                        type="button"
                        title="Heading 1"
                        class="px-3 py-2 shadow-xs transition-all"
                        :class="[editor?.isActive('heading', {level: 1}) ? 'bg-indigo-500' : 'hover:bg-primary hover:text-primary-foreground hover:bg-primary/90']">
                    <i class="ri-h-1"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleHeading({level: 2}).run()"
                        type="button"
                        title="Heading 2"
                        class="px-3 py-2 shadow-xs transition-all"
                        :class="[editor?.isActive('heading', {level: 2}) ? 'bg-indigo-500' : 'hover:bg-primary hover:text-primary-foreground hover:bg-primary/90']">
                    <i class="ri-h-2"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleHeading({level: 3}).run()"
                        type="button"
                        title="Heading 3"
                        class="px-3 py-2 shadow-xs transition-all"
                        :class="[editor?.isActive('heading', {level: 3}) ? 'bg-indigo-500' : 'hover:bg-primary hover:text-primary-foreground hover:bg-primary/90']">
                    <i class="ri-h-3"></i>
                </button>
            </li>
            <slot name="toolbar" :editor="editor" />
        </menu>
        <EditorContent :editor="<Editor>editor" />
    </div>
</template>

<style>
.markdown strong,
.markdown blockquote,
.markdown h1,
.markdown h2,
.markdown h3,
.markdown a {
    color: white;
}
</style>

<style scoped>
:deep(.tiptap p.is-editor-empty:first-child::before) {
    color: #adb5bd;
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
}

</style>
