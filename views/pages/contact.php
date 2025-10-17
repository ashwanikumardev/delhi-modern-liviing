<?php
$title = 'Contact Us - Delhi Modern Living';
ob_start();
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary-600 to-primary-700 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Contact Us</h1>
            <p class="text-xl md:text-2xl max-w-3xl mx-auto">
                Get in touch with us for any queries or assistance
            </p>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                
                <?php if (isset($_SESSION['flash_success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['flash_error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?= url('contact') ?>" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="phone" name="phone"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <select id="subject" name="subject"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Select a subject</option>
                            <option value="booking">Booking Inquiry</option>
                            <option value="support">Technical Support</option>
                            <option value="complaint">Complaint</option>
                            <option value="suggestion">Suggestion</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea id="message" name="message" rows="5" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="Please describe your inquiry in detail..."></textarea>
                    </div>
                    
                    <button type="submit"
                            class="w-full bg-primary-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-primary-700 transition duration-300">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Message
                    </button>
                </form>
            </div>
            
            <!-- Contact Information -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                
                <div class="space-y-6 mb-8">
                    <div class="flex items-start">
                        <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Office Address</h3>
                            <p class="text-gray-600">
                                123 Business Center, Connaught Place<br>
                                New Delhi - 110001, India
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-phone text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Phone Numbers</h3>
                            <p class="text-gray-600">
                                <a href="tel:+917654353464" class="hover:text-primary-600">+91-7654353464</a><br>
                                <a href="tel:+919876543210" class="hover:text-primary-600">+91-9876543210</a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-envelope text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Email Addresses</h3>
                            <p class="text-gray-600">
                                <a href="mailto:info@delhimodernliving.com" class="hover:text-primary-600">info@delhimodernliving.com</a><br>
                                <a href="mailto:support@delhimodernliving.com" class="hover:text-primary-600">support@delhimodernliving.com</a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-clock text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Business Hours</h3>
                            <p class="text-gray-600">
                                Monday - Friday: 9:00 AM - 7:00 PM<br>
                                Saturday: 10:00 AM - 6:00 PM<br>
                                Sunday: 11:00 AM - 4:00 PM
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-700 transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="bg-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-pink-700 transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="bg-blue-400 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-500 transition duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="bg-blue-800 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-900 transition duration-300">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="https://wa.me/917654353464" target="_blank" class="bg-green-500 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-green-600 transition duration-300">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Google Maps Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Find Us</h2>
            <p class="text-lg text-gray-600">Visit our office for personalized assistance</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="h-96">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3501.674862842267!2d77.21787831508236!3d28.63124998240764!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd0683d1e6d1%3A0x8e3f4b2b7c5a8b9c!2sConnaught%20Place%2C%20New%20Delhi%2C%20Delhi!5e0!3m2!1sen!2sin!4v1634567890123!5m2!1sen!2sin"
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <div class="p-6 bg-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Delhi Modern Living Office</h3>
                        <p class="text-gray-600">123 Business Center, Connaught Place, New Delhi - 110001</p>
                    </div>
                    <a href="https://maps.google.com/?q=Connaught+Place+New+Delhi" 
                       target="_blank"
                       class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition duration-300">
                        <i class="fas fa-directions mr-2"></i>
                        Get Directions
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
