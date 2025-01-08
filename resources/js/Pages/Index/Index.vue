<template>
    <div class="home-page">
        <div class="logo-container">
            <img src="@/assets/AutoD.webp" alt="AutoD Logo" class="logo" />
        </div>

        <div class="link-container">
            <router-link to="/listing" class="listings-link">
                Browse Car Listings
            </router-link>
        </div>
    </div>
</template>

<script>

</script>

<style scoped>
.home-page {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 85vh;
    background-color: rgb(255, 255, 255);
}

.logo-container {
    animation: zoomIn 1s ease-in-out;
}

.logo {
    width: 400px;
    height: auto;
}

@keyframes zoomIn {
    from {
        transform: scale(0.5);
        opacity: 0;
    }

    to {
        transform: scale(1);
        opacity: 1;
    }
}

.link-container {
    margin-top: 20px;
}

.listings-link {
    text-decoration: none;
    padding: 10px 20px;
    color: #fff;
    background-color: rgb(79 70 229 / var(--tw-text-opacity, 1));
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.listings-link:hover {
    background-color: rgb(85 80 270 / var(--tw-text-opacity, 1));
}
</style>
