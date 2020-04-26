<template>

    <div>
        <h5 class="title mb-5">
            <i class="fa fa-user"></i>
            User management
            <v-btn class="float-right" :to="{ name: 'user add' }" small color="primary">Add New User</v-btn>
        </h5>
        <v-row>
            <v-col>
                <router-view></router-view>
            </v-col>
        </v-row>
    </div>
</template>