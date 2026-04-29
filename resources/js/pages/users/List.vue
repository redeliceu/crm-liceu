<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import user from '@/routes/user';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { ShieldPlusIcon, StarIcon, Trash2Icon, UserRoundPlusIcon } from 'lucide-vue-next';
import Register from '../auth/Register.vue';

type usersType = {
    created_at: string;
    email: string;
    id: number;
    name: string;
    user_flag_id: number;
}

const formIsOpen = ref<boolean>(false)
const adminDialogOpen = ref<boolean>(false)


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'users',
        href: user.list().url,
    },
];

const users = ref<usersType[]>([]);

const selectedUser = ref<usersType>();


async function getUsers() {
    try {
        const response = await axios.get(`${import.meta.env.VITE_API_URL}/api/users`);
        console.log(response.data.data);
        users.value = response.data.data;
    } catch (error) {
        console.error(error);
    }
}

function handleDialog() {
    formIsOpen.value = !formIsOpen.value;
}



function handleAdminDialog(data: usersType) {
    selectedUser.value = data;
    adminDialogOpen.value = !adminDialogOpen.value;
}

function handleUserFlag(id: number, flag_id: number) {
    console.log(id);

    axios.post(`${import.meta.env.VITE_API_URL}/api/user/${id}/flag/${flag_id}`)
        .then((response) => {
            console.log(response.data);
            getUsers();
            handleAdminDialog({} as usersType);
        })
        .catch((error) => {
            console.error(error);
        });
}

onMounted(() => {

    getUsers();
});

</script>

<template>

    <Head title="Lista de Usuários" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 w-full items-end">
            <button
                class="bg-blue-600 py-2 px-3 max-w-max text-sm font-semibold text-white rounded-md flex gap-3 hover:bg-blue-500 cursor-pointer"
                v-on:click="handleDialog">
                <UserRoundPlusIcon size="16" />
                adicionar usuário
            </button>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-center">ID</TableHead>
                        <TableHead class="text-center">Nome</TableHead>
                        <TableHead class="text-center">Email</TableHead>
                        <TableHead class="text-center">Nível</TableHead>
                        <TableHead class="text-center">Criado em</TableHead>
                        <TableHead class="text-center"></TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="user in users" :key="user.id">
                        <TableCell class="text-center">{{ user.id }}</TableCell>
                        <TableCell class="text-center flex items-center justify-center gap-2">
                            <StarIcon fill="gold" color="gold" size="15" v-if="user.user_flag_id === 2" />
                            {{ user.name }}
                        </TableCell>
                        <TableCell class="text-center">{{ user.email }}</TableCell>
                        <TableCell class="text-center">
                            {{ user.user_flag_id === 1 ? 'usuário' : 'admin' }}
                        </TableCell>
                        <TableCell class="text-center">
                            {{ new Date(user.created_at).toLocaleDateString('pt-BR') }}
                        </TableCell>

                        <TableCell class="text-start flex  gap-2">
                            <button class="cursor-pointer" title="excluir usuário">
                                <Trash2Icon color="red" size="20px" />
                            </button>
                            <button class="cursor-pointer" title="tornar adm" v-on:click="handleAdminDialog(user)" v-if="user.user_flag_id !== 2">
                                <ShieldPlusIcon color="green" size="20px" />
                            </button>
                        </TableCell>
                    </TableRow>

                    <TableRow v-if="users.length === 0">
                        <TableCell colspan="5" class="h-24 text-center">
                            No results.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <dialog
            class="open:bg-gray-800/60 open:text-center open:fixed open:z-100 open:w-screen open:h-screen open:flex "
            :open="formIsOpen" @click.self="handleDialog">
            <div class="m-auto max-w-lg w-full">
                <Register />
            </div>
        </dialog>

        <dialog
            class="open:bg-gray-800/60 open:text-center open:fixed open:z-100 open:w-screen open:h-screen open:flex "
            :open="adminDialogOpen" @click.self="handleAdminDialog">
            <div class="m-auto max-w-lg w-full bg-white py-6 rounded-3xl">
                <h1 class="font-semibold">Tornar <span class="text-blue-500">{{ selectedUser?.name }}</span> em
                    administrador?</h1>

                <div class="flex gap-3 justify-center mt-3">
                    <button
                        class="bg-green-500 hover:bg-green-400 cursor-pointer py-1 px-3 rounded font-semibold text-white"
                        v-on:click="selectedUser && handleUserFlag(selectedUser.id, 2)">
                        confirmar
                    </button>
                    <button
                        class="bg-red-500 hover:bg-red-400 cursor-pointer py-1 px-3 rounded font-semibold text-white"
                        v-on:click="handleAdminDialog">
                        cancelar
                    </button>
                </div>
            </div>
        </dialog>
    </AppLayout>
</template>
