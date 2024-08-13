<template>
	<nav>
		<a href="/">
			<svg height="100%" viewBox="0 0 512.784 512.784" xmlns="http://www.w3.org/2000/svg">
				<path d="m256.49 512.611c-39.223 0-76.087-8.051-109.57-23.929-9.98-4.733-14.234-16.66-9.501-26.641s16.661-14.234 26.641-9.501c182.603 84.249 373.961-104.925 287.358-288.361-10.496-24.22 23.859-40.738 36.143-17.138 81.565 167.692-45.06 367.114-231.071 365.57zm-204.366-135.959c9.964-4.768 14.175-16.71 9.407-26.674-86.823-182.155 104.549-375.416 287.392-288.294 9.98 4.732 21.906.477 26.638-9.503s.479-21.908-9.501-26.641c-218.254-102.475-442.869 124.354-340.608 341.706 4.782 10.061 16.93 14.133 26.672 9.406zm-17.492 130.101s472-472 472-472c7.811-7.811 7.811-20.474 0-28.284s-20.474-7.811-28.284 0c0 0-472 472-472 472-7.811 7.811-7.811 20.474 0 28.284 7.811 7.811 20.474 7.811 28.284 0z"/>
			</svg>

			<h2>The Void</h2>
		</a>
	</nav>

	<header>
		<img :src="userImage" alt="User Image" />

		<h1>AndyM84 Screams at the Void</h1>
	</header>

	<section id="poster">
		<div>
			<h3>What's on your mind?</h3>

			<h4>140</h4>
		</div>

		<textarea placeholder="Scream at the void..."></textarea>

		<button>Scream</button>
	</section>

	<section id="screams">
		<h3>Screams</h3>

		<div v-for="scream in screams" :key="scream.id" class="scream-row">
			<div class="fake-image">&nbsp;</div>

			<div class="scream-container">
				<p>
					{{ scream.body }}
				</p>

				<span>{{ scream.relativeCreatedAt }}</span>
			</div>
		</div>
	</section>
</template>

<script setup lang="ts">
import userImage from '@/assets/user.png';

interface Scream {
	id: string;
	body: string;
	createdAt: string;
	relativeCreatedAt: string;
}

const screams: Scream[] = [
	{
		id: '1',
		body: 'This is a test scream.',
		createdAt: '2021-09-01T12:00:00.000Z',
		relativeCreatedAt: 'about 1 hour ago',
	},
	{
		id: '2',
		body: 'This is another test scream.',
		createdAt: '2021-09-01T12:00:00.000Z',
		relativeCreatedAt: 'about 1 hour ago',
	},
	{
		id: '3',
		body: 'This is a third test scream.',
		createdAt: '2021-09-01T12:00:00.000Z',
		relativeCreatedAt: 'about 1 hour ago',
	},
];
</script>

<style>
main {
	display: flex;
	align-content: start;
	flex-direction: column;
	justify-content: center;

	nav {
		width: 100%;
		height: 50px;
		padding: 10px;
		cursor: pointer;
		margin: 0 auto 25px auto;
		background-color: var(--color-blue-2);

		a {
			height: 100%;
			flex-direction: row;
			align-items: center;
			display: inline-flex;

			svg {
				height: 100%;
				fill: var(--vt-c-white-soft);
				margin-right: 15px;
			}

			h2 {
				line-height: 50px;
				color: var(--vt-c-white-soft);
			}
		}

		a:hover {
			background-color: var(--color-blue-2);
		}
	}

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
		margin: 0 auto;
		padding: 0 25px;
		background-color: var(--vt-c-white);

		div:first-child {
			display: flex;
			font-size: 20px;
			margin-top: 20px;
			align-items: center;
			justify-content: space-between;

			h4 {
				font-weight: 700;
				color: var(--color-grey-3);
			}
		}

		textarea {
			width: 100%;
			height: 50px;
			resize: none;
			display: block;
			border-radius: 5px;
			border: 1px solid var(--color-grey-6);
			background-color: var(--vt-c-white-soft);
		}

		button {
			border: none;
			padding: 5px;
			display: block;
			margin: 10px 0 0 auto;
			background-color: var(--color-button-background);
			color: var(--color-button-text);
			border-radius: 5px;
		}

		button:active {
			color: var(--color-grey-5);
			background-color: var(--color-grey-6);
		}

		button:disabled {
			color: var(--color-grey-5);
			background-color: var(--color-grey-6);
		}
	}

	section#screams {
		width: 720px;
		padding: 25px;
		margin: 0 auto;
		background-color: var(--vt-c-white);

		h3 {
			font-weight: 600;
			border-bottom: 1px solid var(--color-grey-3);
		}

		.scream-row {
			display: flex;
			padding: 15px 0;
			border-bottom: 1px solid var(--color-grey-3);

			.fake-image {
				width: 50px;
				height: 50px;
				background-color: var(--color-grey-6);
			}

			.scream-container {
				margin-left: 10px;

				p {
					margin: 0;
				}

				span {
					font-size: 12px;
					color: var(--color-grey-3);
				}
			}
		}
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
