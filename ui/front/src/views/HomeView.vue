<template>
	<header>
		<img :src="userImage" alt="User Image" />

		<h1>AndyM84 Screams at the Void</h1>
	</header>

	<section id="poster">
		<textarea placeholder="Scream at the void..."></textarea>
		<button>Post</button>
	</section>

	<section id="screams">

	</section>
</template>

<script setup lang="ts">
import userImage from '@/assets/user.png';
</script>

<style>
main {
	display: flex;
	align-content: start;
	flex-direction: column;
	justify-content: center;

	header {
		gap: 15px;
		width: 720px;
		height: 100px;
		display: flex;
		margin: 0 auto;
		padding-left: 10px;
		align-items: center;
		justify-content: start;

		img {
			width: 75px;
		}

		h1 {
			margin: auto 0;
			line-height: 75px;
		}
	}

	section#poster {
		width: 720px;
		height: 100px;
		margin: 0 auto;
		background-color: var(--vt-c-white);

		textarea {
			width: 75%;
			height: 75%;
			background-color: var(--vt-c-white-soft);
			border: 1px solid var(--color-grey-6);
			border-radius: 5px;
			resize: none;
		}

		button {
			border: none;
			background-color: var(--color-button-background);
			color: var(--color-button-text);
			border-radius: 5px;
		}
	}

	section#screams {
		width: 720px;
		height: 100px;
		margin: 0 auto;
		background-color: var(--vt-c-white);
	}

	@media only screen and (max-width: 720px) {
		header {
			width: 500px;

			h1 {
				font-size: 20px;
			}
		}

		section#poster {
			width: 500px;
		}

		section#screams {
			width: 500px;
		}
	}
}
</style>
