<?php include 'includes/header.php'; ?>

<style>
/* =====================
   HOW IT WORKS – HERO WITH IMAGE
===================== */
.how-hero {
    position: relative;
    background:
        linear-gradient(
             rgba(249, 213, 252, 0.75),
            rgba(126, 42, 132, 0.75)
        ),
        url("assets/images/howitworkshero.jpg") center / cover no-repeat;
    padding: 100px 20px;
    text-align: center;
    color: #ffffff;
}

.how-hero h1 {
    font-size: 36px;
    margin-bottom: 10px;
}

.how-hero p {
    font-size: 15.5px;
    max-width: 650px;
    margin: auto;
    line-height: 1.7;
    opacity: 0.95;
}

/* =====================
   STEPS SECTION
===================== */
.how-steps {
    background: #ffffff;
    padding: 80px 20px;
}

.how-grid {
    max-width: 1100px;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
}

.how-card {
    background: #f9f9fb;
    padding: 35px 25px;
    border-radius: 14px;
    text-align: center;
    box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
}

.how-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 45px rgba(0,0,0,0.1);
}

.step-number {
    width: 46px;
    height: 46px;
    line-height: 46px;
    background: #5a0c5f;
    color: #fff;
    border-radius: 50%;
    font-weight: 600;
    font-size: 18px;
    margin: 0 auto 15px;
}

.how-card h3 {
    font-size: 18px;
    color: #5a0c5f;
    margin-bottom: 10px;
}

.how-card p {
    font-size: 14.5px;
    color: #555;
    line-height: 1.7;
}

/* =====================
   CTA SECTION
===================== */
.how-cta {
    background: linear-gradient(135deg, #5a0c5f, #8e24aa);
    color: #fff;
    text-align: center;
    padding: 80px 20px;
}

.how-cta h2 {
    font-size: 30px;
    margin-bottom: 10px;
}

.how-cta p {
    font-size: 15px;
    margin-bottom: 25px;
}

.how-cta .btn {
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

    .how-grid {
        grid-template-columns: 1fr 1fr;
    }

    .how-hero h1 {
        font-size: 30px;
    }
}

/* =====================
   MOBILE VIEW (≤768px)
===================== */
@media (max-width: 768px) {

    .how-hero {
        padding: 70px 15px;
    }

    .how-hero h1 {
        font-size: 26px;
    }

    .how-hero p {
        font-size: 14px;
    }

    .how-steps {
        padding: 60px 15px;
    }

    .how-grid {
        grid-template-columns: 1fr;
    }

    .how-card {
        padding: 28px 20px;
    }

    .how-card h3 {
        font-size: 17px;
    }

    .how-card p {
        font-size: 14px;
    }

    .how-cta h2 {
        font-size: 24px;
    }

    .how-cta .btn {
        width: 100%;
        max-width: 260px;
    }
}

/* =====================
   SMALL MOBILE (≤480px)
===================== */
@media (max-width: 480px) {

    .how-hero h1 {
        font-size: 22px;
    }

    .how-card p {
        font-size: 13.5px;
    }
}
</style>

<!-- HERO -->
<section class="how-hero">
    <h1>How It Works</h1>
    <p>
        Karadi Samaaj Matrimony follows a simple, secure, and transparent
        process to help you find the right life partner within the community.
    </p>
</section>

<!-- STEPS -->
<section class="how-steps">
    <div class="how-grid">

        <div class="how-card">
            <div class="step-number">1</div>
            <h3>Register</h3>
            <p>
                Create your profile by filling in personal, family,
                and education details.
            </p>
        </div>

        <div class="how-card">
            <div class="step-number">2</div>
            <h3>Admin Verification</h3>
            <p>
                All profiles and documents are manually verified by
                Admin for authenticity.
            </p>
        </div>

        <div class="how-card">
            <div class="step-number">3</div>
            <h3>Search & Send Interest</h3>
            <p>
                Search verified profiles and send interest to
                suitable matches.
            </p>
        </div>

        <div class="how-card">
            <div class="step-number">4</div>
            <h3>Connect After Match</h3>
            <p>
                Chat and connect only after mutual interest acceptance,
                ensuring privacy and safety.
            </p>
        </div>

    </div>
</section>

<!-- CTA -->
<section class="how-cta">
    <h2>Start Your Journey Today</h2>
    <p>Join the trusted Karadi Samaaj Matrimony platform</p>
    <a href="auth/register.php" class="btn">Register</a>
</section>

<?php include 'includes/footer.php'; ?>
