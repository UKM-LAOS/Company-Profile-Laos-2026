<script setup lang="ts">
import AppButton from '@/Components/AppButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Image from '@tiptap/extension-image';
import Youtube from '@tiptap/extension-youtube';
import StarterKit from '@tiptap/starter-kit';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import { onBeforeUnmount, ref, watch } from 'vue';

const props = defineProps<{
    modelValue: string;
    error?: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit,
        Image.configure({
            inline: true,
            allowBase64: true,
        }),
        Youtube.configure({
            controls: false,
        }),
    ],
    onUpdate: () => {
        emit('update:modelValue', editor.value?.getHTML() || '');
    },
    editorProps: {
        attributes: {
            class: 'prose prose-sm sm:prose-base focus:outline-none max-w-none min-h-[350px] p-5 text-slate-800 dark:text-slate-200 tiptap-editor',
        },
    },
});

watch(
    () => props.modelValue,
    (value) => {
        const isSame = editor.value?.getHTML() === value;
        if (isSame) {
            return;
        }
        editor.value?.commands.setContent(value, false);
    },
);

onBeforeUnmount(() => {
    editor.value?.destroy();
});

const showImageModal = ref(false);
const imageInputType = ref<'link' | 'upload'>('link');
const imageUrl = ref('');
const imageFile = ref<File | null>(null);

const showYoutubeModal = ref(false);
const youtubeUrl = ref('');

const openImageModal = () => {
    imageUrl.value = '';
    imageFile.value = null;
    imageInputType.value = 'link';
    showImageModal.value = true;
};

const closeImageModal = () => {
    showImageModal.value = false;
};

const openYoutubeModal = () => {
    youtubeUrl.value = '';
    showYoutubeModal.value = true;
};

const closeYoutubeModal = () => {
    showYoutubeModal.value = false;
};

const handleImageFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        imageFile.value = target.files[0];
    }
};

const insertImage = () => {
    if (imageInputType.value === 'link' && imageUrl.value) {
        editor.value?.chain().focus().setImage({ src: imageUrl.value }).run();
        closeImageModal();
    } else if (imageInputType.value === 'upload' && imageFile.value) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const result = e.target?.result;
            if (typeof result === 'string') {
                editor.value?.chain().focus().setImage({ src: result }).run();
                closeImageModal();
            }
        };
        reader.readAsDataURL(imageFile.value);
    }
};

const insertYoutube = () => {
    if (youtubeUrl.value && editor.value) {
        editor.value.commands.setYoutubeVideo({ src: youtubeUrl.value });
        closeYoutubeModal();
    }
};
</script>

<template>
    <div>
        <div
            class="relative flex flex-col overflow-hidden rounded-xl border bg-white dark:bg-slate-900"
            :class="[
                error
                    ? 'border-rose-500'
                    : 'border-slate-300 focus-within:border-emerald-500 focus-within:ring-1 focus-within:ring-emerald-500 dark:border-slate-700 dark:focus-within:border-emerald-500',
            ]"
        >
            <!-- Toolbar -->
            <div
                v-if="editor"
                class="flex flex-wrap items-center gap-1 border-b border-slate-200 bg-slate-50/80 p-2 dark:border-slate-700/80 dark:bg-slate-800"
            >
                <button
                    type="button"
                    :class="[
                        'min-w-8 rounded-lg p-1.5 text-sm font-semibold transition-colors',
                        editor.isActive('bold')
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300'
                            : 'text-slate-600 hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700',
                    ]"
                    title="Bold"
                    @click="editor.chain().focus().toggleBold().run()"
                >
                    B
                </button>
                <button
                    type="button"
                    :class="[
                        'min-w-8 rounded-lg p-1.5 text-sm font-semibold italic transition-colors',
                        editor.isActive('italic')
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300'
                            : 'text-slate-600 hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700',
                    ]"
                    title="Italic"
                    @click="editor.chain().focus().toggleItalic().run()"
                >
                    I
                </button>
                <button
                    type="button"
                    :class="[
                        'min-w-8 rounded-lg p-1.5 text-sm font-semibold line-through transition-colors',
                        editor.isActive('strike')
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300'
                            : 'text-slate-600 hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700',
                    ]"
                    title="Strike"
                    @click="editor.chain().focus().toggleStrike().run()"
                >
                    S
                </button>

                <div class="mx-1 h-5 w-px bg-slate-300 dark:bg-slate-600" />

                <button
                    type="button"
                    :class="[
                        'rounded-lg px-2.5 py-1.5 text-sm font-bold transition-colors',
                        editor.isActive('heading', { level: 2 })
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300'
                            : 'text-slate-600 hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700',
                    ]"
                    title="Heading 2"
                    @click="
                        editor.chain().focus().toggleHeading({ level: 2 }).run()
                    "
                >
                    H2
                </button>
                <button
                    type="button"
                    :class="[
                        'rounded-lg px-2.5 py-1.5 text-sm font-bold transition-colors',
                        editor.isActive('heading', { level: 3 })
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300'
                            : 'text-slate-600 hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700',
                    ]"
                    title="Heading 3"
                    @click="
                        editor.chain().focus().toggleHeading({ level: 3 }).run()
                    "
                >
                    H3
                </button>

                <div class="mx-1 h-5 w-px bg-slate-300 dark:bg-slate-600" />

                <button
                    type="button"
                    :class="[
                        'flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm transition-colors',
                        editor.isActive('bulletList')
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300'
                            : 'text-slate-600 hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700',
                    ]"
                    title="Bullet List"
                    @click="editor.chain().focus().toggleBulletList().run()"
                >
                    <span class="font-bold">•</span> List
                </button>
                <button
                    type="button"
                    :class="[
                        'flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm transition-colors',
                        editor.isActive('orderedList')
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300'
                            : 'text-slate-600 hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700',
                    ]"
                    title="Ordered List"
                    @click="editor.chain().focus().toggleOrderedList().run()"
                >
                    <span class="font-bold">1.</span> List
                </button>
                <button
                    type="button"
                    :class="[
                        'flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm transition-colors',
                        editor.isActive('blockquote')
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300'
                            : 'text-slate-600 hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700',
                    ]"
                    title="Quote"
                    @click="editor.chain().focus().toggleBlockquote().run()"
                >
                    <span class="font-bold">"</span> Quote
                </button>

                <div class="mx-1 h-5 w-px bg-slate-300 dark:bg-slate-600" />

                <button
                    type="button"
                    class="rounded-lg px-2.5 py-1.5 text-sm text-slate-600 transition-colors hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700"
                    title="Add Image"
                    @click="openImageModal"
                >
                    🖼️ Image
                </button>
                <button
                    type="button"
                    class="rounded-lg px-2.5 py-1.5 text-sm text-slate-600 transition-colors hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700"
                    title="Embed YouTube"
                    @click="openYoutubeModal"
                >
                    ▶️ YouTube
                </button>
            </div>

            <!-- Editor Content -->
            <EditorContent
                :editor="editor"
                class="flex-1 overflow-y-auto"
            />
        </div>
        <p v-if="error" class="mt-1.5 text-sm text-rose-500">
            {{ error }}
        </p>

        <!-- Image Modal -->
        <Modal :show="showImageModal" max-width="md" @close="closeImageModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">
                    Sisipkan Gambar
                </h2>

                <div class="mb-5 flex space-x-4 border-b border-slate-200 dark:border-slate-700">
                    <button
                        type="button"
                        class="pb-2 text-sm font-medium transition-colors"
                        :class="imageInputType === 'link' ? 'border-b-2 border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'"
                        @click="imageInputType = 'link'"
                    >
                        URL Tautan
                    </button>
                    <button
                        type="button"
                        class="pb-2 text-sm font-medium transition-colors"
                        :class="imageInputType === 'upload' ? 'border-b-2 border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'"
                        @click="imageInputType = 'upload'"
                    >
                        Unggah File
                    </button>
                </div>

                <div v-if="imageInputType === 'link'" class="space-y-4">
                    <div>
                        <InputLabel value="URL Gambar" required />
                        <TextInput
                            v-model="imageUrl"
                            type="url"
                            class="mt-1 block w-full"
                            placeholder="https://contoh.com/gambar.jpg"
                            @keyup.enter="insertImage"
                        />
                    </div>
                </div>
                <div v-else class="space-y-4">
                    <div>
                        <InputLabel value="Pilih File Gambar" required />
                        <input
                            type="file"
                            accept="image/*"
                            class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/50 dark:file:text-emerald-400 dark:text-slate-300"
                            @change="handleImageFileChange"
                        />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeImageModal">Batal</SecondaryButton>
                    <AppButton
                        type="button"
                        variant="primary"
                        :disabled="(imageInputType === 'link' && !imageUrl) || (imageInputType === 'upload' && !imageFile)"
                        @click="insertImage"
                    >
                        Sisipkan
                    </AppButton>
                </div>
            </div>
        </Modal>

        <!-- YouTube Modal -->
        <Modal :show="showYoutubeModal" max-width="md" @close="closeYoutubeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">
                    Sisipkan Video YouTube
                </h2>

                <div>
                    <InputLabel value="URL Video YouTube" required />
                    <TextInput
                        v-model="youtubeUrl"
                        type="url"
                        class="mt-1 block w-full"
                        placeholder="https://www.youtube.com/watch?v=..."
                        @keyup.enter="insertYoutube"
                    />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeYoutubeModal">Batal</SecondaryButton>
                    <AppButton
                        type="button"
                        variant="primary"
                        :disabled="!youtubeUrl"
                        @click="insertYoutube"
                    >
                        Sisipkan
                    </AppButton>
                </div>
            </div>
        </Modal>
    </div>
</template>

<style>
/* Basic Prose Styles for Tiptap */
.tiptap-editor h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    line-height: 1.3333333;
}
.tiptap-editor h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    line-height: 1.6;
}
.tiptap-editor p {
    margin-top: 1em;
    margin-bottom: 1em;
}
.tiptap-editor ul {
    list-style-type: disc;
    padding-left: 1.5em;
    margin-top: 1em;
    margin-bottom: 1em;
}
.tiptap-editor ol {
    list-style-type: decimal;
    padding-left: 1.5em;
    margin-top: 1em;
    margin-bottom: 1em;
}
.tiptap-editor blockquote {
    font-weight: 500;
    font-style: italic;
    color: #475569;
    border-left-width: 0.25rem;
    border-left-color: #cbd5e1;
    quotes: "\201C""\201D""\2018""\2019";
    margin-top: 1.6em;
    margin-bottom: 1.6em;
    padding-left: 1em;
}
.dark .tiptap-editor blockquote {
    color: #94a3b8;
    border-left-color: #334155;
}
.tiptap-editor img {
    border-radius: 0.5rem;
    max-width: 50%;
    height: auto;
    margin-top: 2em;
    margin-bottom: 2em;
    margin-left: auto;
    margin-right: auto;
    display: block;
}
.tiptap-editor iframe {
    width: 100%;
    aspect-ratio: 16 / 9;
    border-radius: 0.5rem;
    margin-top: 2em;
    margin-bottom: 2em;
}
.tiptap-editor p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    float: left;
    color: #94a3b8;
    pointer-events: none;
    height: 0;
}
</style>
