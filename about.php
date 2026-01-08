<?php include 'includes/header.php'; ?>

<style>
/* =====================
   ABOUT HERO (IMAGE + OVERLAY)
===================== */
.about-hero {
    position: relative;
    background:
        linear-gradient(
            rgba(249, 213, 252, 0.75),
            rgba(126, 42, 132, 0.75)
        ),
        url("assets/images/about.jpg") center / cover no-repeat;
    padding: 100px 20px;
    text-align: center;
    color: #ffffff;
}

.about-hero h1 {
    font-size: 36px;
    margin-bottom: 10px;
}

.about-hero p {
    font-size: 15.5px;
    max-width: 700px;
    margin: auto;
    line-height: 1.7;
    opacity: 0.95;
}

/* =====================
   ABOUT CARDS
===================== */
.about-cards {
    background: #ffffff;
    padding: 80px 20px;
}

.about-card-grid {
    max-width: 1100px;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.about-card {
    background: #f9f9fb;
    padding: 35px 25px;
    border-radius: 14px;
    text-align: center;
    box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
}

.about-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 45px rgba(0,0,0,0.1);
}

.about-card h3 {
    font-size: 20px;
    color: #5a0c5f;
    margin-bottom: 12px;
}

.about-card p {
    font-size: 15px;
    color: #555;
    line-height: 1.8;
}

/* =====================
   ABOUT CTA
===================== */
.about-cta {
    background: linear-gradient(135deg, #5a0c5f, #8e24aa);
    color: #ffffff;
    text-align: center;
    padding: 80px 20px;
}

.about-cta h2 {
    font-size: 30px;
    margin-bottom: 10px;
}

.about-cta p {
    font-size: 15px;
    margin-bottom: 25px;
}

.about-cta .btn {
    background: #ffffff;
    color: #5a0c5f;
    padding: 12px 30px;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
}

/* =====================
   TABLET VIEW (≤1024px)
===================== */
@media (max-width: 1024px) {

    .about-card-grid {
        grid-template-columns: 1fr 1fr;
    }

    .about-hero h1 {
        font-size: 30px;
    }
}

/* =====================
   MOBILE VIEW (≤768px)
===================== */
@media (max-width: 768px) {

    .about-hero {
        padding: 70px 15px;
    }

    .about-hero h1 {
        font-size: 26px;
    }

    .about-hero p {
        font-size: 14px;
    }

    .about-cards {
        padding: 60px 15px;
    }

    .about-card-grid {
        grid-template-columns: 1fr;
    }

    .about-card h3 {
        font-size: 18px;
    }

    .about-card p {
        font-size: 14px;
    }

    .about-cta h2 {
        font-size: 24px;
    }

    .about-cta .btn {
        width: 100%;
        max-width: 260px;
    }
}

/* =====================
   SMALL MOBILE (≤480px)
===================== */
@media (max-width: 480px) {

    .about-hero h1 {
        font-size: 22px;
    }

    .about-hero p {
        font-size: 13.5px;
    }
}
</style>

<!-- HERO -->
<section class="about-hero">
    <h1>About Karadi Samaaj Matrimony</h1>
    <p>
        A trusted, community-focused matrimony platform built exclusively
        for Karadi Samaaj members, ensuring privacy, authenticity,
        and meaningful connections.
    </p>
</section>

<!-- ABOUT CARDS -->
<section class="about-cards">
    <div class="about-card-grid">

        <div class="about-card">
            <h3>Who We Are</h3>
            <p>
                Karadi Samaaj Matrimony is a community-driven initiative
                created to help families connect with confidence,
                trust, and shared values.
            </p>
        </div>

        <div class="about-card">
            <h3>Our Vision</h3>
            <p>
                To create a safe, transparent, and respectful matrimony
                platform that preserves Karadi Samaaj traditions
                while embracing modern technology.
            </p>
        </div>

        <div class="about-card">
            <h3>Why Choose Us</h3>
            <p>
                We offer admin-verified profiles, privacy-first controls,
                and an exclusive platform dedicated only to
                Karadi Samaaj members.
            </p>
        </div>

    </div>
</section>

<!-- CTA -->
<section class="about-cta">
    <h2>Begin Your Journey With Trust</h2>
    <p>Join Karadi Samaaj Matrimony and find your life partner</p>
    <a href="auth/register.php" class="btn">Register Now</a>
</section>

<?php include 'includes/footer.php'; ?>
