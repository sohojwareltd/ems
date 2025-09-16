<!DOCTYPE html>
    {{-- @dd($product->pdf_file) --}}
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' 'unsafe-inline' 'unsafe-eval' https:; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https:;">
    <title>PDF Viewer</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
            color: #ffffff;
            overflow-x: hidden;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
            -webkit-tap-highlight-color: transparent;
        }

        .pdf-container {
            width: 100vw;
            min-height: 100vh;
            position: relative;
            padding: 15px;
        }

        .main-content {
            position: relative;
            overflow: visible;
        }

        .pdf-viewer {
            width: 100%;
            min-height: 80vh;
            position: relative;
            background: transparent;
            border-radius: 12px;
            overflow: hidden;
        }

        .pdf-canvas {
            width: 100%;
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background: transparent;
            padding: 15px 0;
        }

        .canvas-container {
            text-align: center;
            padding: 15px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            width: 100%;
            max-width: 100%;
            animation: fadeInScale 0.6s ease-out;
            transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .canvas-container:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* Responsive PDF sizing */
        @media (min-width: 1400px) {
            .canvas-container {
                max-width: 1200px;
            }
        }

        @media (min-width: 1200px) and (max-width: 1399px) {
            .canvas-container {
                max-width: 1000px;
            }
        }

        @media (min-width: 768px) and (max-width: 1199px) {
            .canvas-container {
                max-width: 800px;
            }
        }

        .canvas-container canvas {
            width: 100%;
            height: auto;
            max-width: 100%;
            border-radius: 8px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
            -webkit-tap-highlight-color: transparent;
        }

        .loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
            color: #64b5f6;
            animation: fadeIn 1s ease-out;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(100, 181, 246, 0.2);
            border-top: 4px solid #64b5f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        .loading-progress {
            margin-top: 15px;
            width: 200px;
        }

        .progress-bar {
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #64b5f6, #2196f3);
            border-radius: 2px;
            width: 0%;
            transition: width 0.8s ease-out;
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% { 
                transform: scale(1);
                opacity: 1;
            }
            50% { 
                transform: scale(1.1);
                opacity: 0.7;
            }
        }

        /* Navigation Overlay */
        .nav-overlay {
            position: fixed;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            pointer-events: none;
            z-index: 100;
            animation: fadeInUp 0.8s ease-out;
        }

        .nav-btn {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            pointer-events: auto;
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 80px;
            justify-content: center;
            will-change: transform;
        }

        .nav-btn:hover {
            background: rgba(100, 181, 246, 0.3);
            border-color: rgba(100, 181, 246, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(100, 181, 246, 0.3);
        }

        .nav-btn:active {
            transform: translateY(0px);
            transition: transform 0.1s ease;
        }

        .nav-btn:disabled {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.2);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
            opacity: 0.6;
        }

        .nav-btn span {
            transition: transform 0.2s ease;
        }

        .nav-btn:hover span:first-child {
            transform: translateX(-1px);
        }

        .nav-btn:hover span:last-child {
            transform: translateX(1px);
        }

        /* Page Info Overlay */
        .page-info-overlay {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            z-index: 100;
            font-size: 0.9rem;
            animation: fadeInDown 0.6s ease-out;
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            will-change: transform;
        }

        .page-info-overlay:hover {
            background: rgba(0, 0, 0, 0.6);
            transform: translateX(-50%) scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        /* Toggle Button */
        .toggle-preview-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(100, 181, 246, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 6px 20px rgba(100, 181, 246, 0.2);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 6px;
            animation: fadeInUp 0.8s ease-out 0.2s both;
            will-change: transform;
        }

        .toggle-preview-btn:hover {
            background: rgba(100, 181, 246, 0.6);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(100, 181, 246, 0.3);
        }

        .toggle-preview-btn:active {
            transform: translateY(0px);
            transition: transform 0.1s ease;
        }

        .toggle-preview-btn span:first-child {
            animation: bounce 3s infinite;
        }

        /* Page Preview Panel */
        .page-preview-panel {
            position: fixed;
            bottom: -300px;
            left: 0;
            right: 0;
            height: 300px;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            transition: bottom 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999;
            overflow: hidden;
            transform: translateY(0);
        }

        .page-preview-panel.active {
            bottom: 0;
            animation: slideInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .page-preview-panel:not(.active) {
            animation: slideOutDown 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .preview-title {
            color: #64b5f6;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .close-preview-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            will-change: transform;
        }

        .close-preview-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
        }

        .close-preview-btn:active {
            transform: scale(0.98);
            transition: transform 0.1s ease;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 12px;
            padding: 15px 20px;
            max-height: 230px;
            overflow-y: auto;
        }

        .preview-item {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid transparent;
            border-radius: 8px;
            padding: 8px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            animation: fadeInScale 0.4s ease-out;
            animation-fill-mode: both;
            will-change: transform;
        }

        .preview-item:nth-child(1) { animation-delay: 0.05s; }
        .preview-item:nth-child(2) { animation-delay: 0.1s; }
        .preview-item:nth-child(3) { animation-delay: 0.15s; }
        .preview-item:nth-child(4) { animation-delay: 0.2s; }
        .preview-item:nth-child(5) { animation-delay: 0.25s; }
        .preview-item:nth-child(6) { animation-delay: 0.3s; }
        .preview-item:nth-child(7) { animation-delay: 0.35s; }
        .preview-item:nth-child(8) { animation-delay: 0.4s; }

        .preview-item:hover {
            border-color: #64b5f6;
            background: rgba(100, 181, 246, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(100, 181, 246, 0.3);
        }

        .preview-item.active {
            border-color: #4caf50;
            background: rgba(76, 175, 80, 0.15);
            transform: scale(1.01);
            box-shadow: 0 2px 10px rgba(76, 175, 80, 0.3);
        }

        .preview-item:active {
            transform: scale(0.99);
            transition: transform 0.1s ease;
        }

        .preview-item canvas {
            width: 100%;
            height: auto;
            border-radius: 6px;
            margin-bottom: 6px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
            -webkit-tap-highlight-color: transparent;
        }

        .preview-item .page-number {
            font-size: 0.8rem;
            font-weight: 600;
            color: #b0bec5;
        }

        .preview-item.active .page-number {
            color: #4caf50;
        }

        /* Custom scrollbar */
        .preview-grid::-webkit-scrollbar {
            width: 6px;
        }

        .preview-grid::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
        }

        .preview-grid::-webkit-scrollbar-thumb {
            background: rgba(100, 181, 246, 0.3);
            border-radius: 6px;
        }

        .preview-grid::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 181, 246, 0.5);
        }

        /* Security and Protection Styles */
        .pdf-container {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .pdf-viewer {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Disable text selection and context menu */
        * {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }

        /* Prevent drag and drop */
        img, canvas, * {
            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
            user-drag: none;
        }

        /* Additional security measures */
        .pdf-container, .pdf-viewer, .canvas-container {
            -webkit-filter: none !important;
            filter: none !important;
            -webkit-transform: none !important;
            transform: none !important;
        }

        /* Prevent copy/paste */
        * {
            -webkit-touch-callout: none !important;
            -webkit-user-select: none !important;
            -khtml-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }

        /* Disable image saving */
        img, canvas {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
            -webkit-user-drag: none !important;
            -khtml-user-drag: none !important;
            -moz-user-drag: none !important;
            -o-user-drag: none !important;
            user-drag: none !important;
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .pdf-container {
                padding: 10px;
            }
            
            .pdf-canvas {
                padding: 10px 0;
            }
            
            .canvas-container {
                padding: 10px;
                border-radius: 8px;
            }
            
            .nav-overlay {
                padding: 0 10px;
            }
            
            .nav-btn {
                padding: 10px 12px;
                font-size: 0.8rem;
                min-width: 70px;
                gap: 4px;
            }
            
            .nav-btn span:last-child {
                display: none;
            }
            
            .page-info-overlay {
                top: 15px;
                padding: 8px 16px;
                font-size: 0.8rem;
            }
            
            .toggle-preview-btn {
                bottom: 15px;
                right: 15px;
                padding: 10px 16px;
                font-size: 0.8rem;
            }
            
            .toggle-preview-btn span:last-child {
                display: none;
            }
            
            .preview-grid {
                grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
                gap: 10px;
                padding: 15px;
            }
            
            .preview-item {
                padding: 6px;
            }
            
            .preview-item .page-number {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 480px) {
            .pdf-container {
                padding: 8px;
            }
            
            .nav-btn {
                padding: 8px 10px;
                min-width: 60px;
            }
            
            .preview-grid {
                grid-template-columns: repeat(auto-fit, minmax(70px, 1fr));
                gap: 8px;
                padding: 12px;
            }
            
            .preview-item {
                padding: 5px;
            }
        }

        /* Beautiful Keyframe Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideInUp {
            from {
                transform: translateY(100%);
            }
            to {
                transform: translateY(0);
            }
        }

        @keyframes slideOutDown {
            from {
                transform: translateY(0);
            }
            to {
                transform: translateY(100%);
            }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-3px);
            }
            60% {
                transform: translateY(-1px);
            }
        }

        /* Smooth page transitions */
        .canvas-container canvas {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Enhanced hover effects for interactive elements */
        .nav-btn, .toggle-preview-btn, .close-preview-btn {
            transform-origin: center;
        }

        /* Floating animation for page info */
        .page-info-overlay {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateX(-50%) translateY(0px);
            }
            50% {
                transform: translateX(-50%) translateY(-3px);
            }
        }

        /* Glow effect on hover for main elements */
        .nav-btn:hover, .toggle-preview-btn:hover {
            filter: drop-shadow(0 0 10px rgba(100, 181, 246, 0.5));
        }

        /* Smooth background transitions */
        body {
            background-size: 400% 400%;
            animation: gradientShift 20s ease infinite;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>
<body>
    <div class="pdf-container">
        <div class="main-content">
            <div class="pdf-viewer">
                <div class="pdf-canvas" id="pdfCanvas">
                    <div class="loading">
                        <div class="spinner"></div>
                        <p>Loading PDF...</p>
                    </div>
                </div>
                
                <!-- Navigation Overlay -->
                <div class="nav-overlay">
                    <button class="nav-btn" id="prevBtn" disabled>
                        <span>◀</span>
                        <span>Previous</span>
                    </button>
                    <button class="nav-btn" id="nextBtn" disabled>
                        <span>Next</span>
                        <span>▶</span>
                    </button>
                </div>
                
                <!-- Page Info Overlay -->
                <div class="page-info-overlay" id="pageInfo">
                    Page 1 of 1
                </div>
                
                <!-- Toggle Preview Button -->
                <button class="toggle-preview-btn" id="togglePreviewBtn">
                    <span>📄</span>
                    <span>Page Previews</span>
                </button>
            </div>
            
            <!-- Page Preview Panel -->
            <div class="page-preview-panel" id="previewPanel">
                <div class="preview-header">
                    <div class="preview-title">📄 Page Previews</div>
                    <button class="close-preview-btn" id="closePreviewBtn">✕ Close</button>
                </div>
                <div class="preview-grid" id="previewGrid"></div>
            </div>
        </div>
    </div>


    <script>
        // Set worker path for PDF.js
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let scale = 1.5;

        // Get DOM elements
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const pdfCanvas = document.getElementById('pdfCanvas');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const pageInfo = document.getElementById('pageInfo');
        const previewGrid = document.getElementById('previewGrid');
        const togglePreviewBtn = document.getElementById('togglePreviewBtn');
        const previewPanel = document.getElementById('previewPanel');
        const closePreviewBtn = document.getElementById('closePreviewBtn');

        // Responsive scale adjustment
        function adjustScale() {
            const isMobile = window.innerWidth <= 768;
            const isSmallMobile = window.innerWidth <= 480;
            const isLargeDesktop = window.innerWidth >= 1400;
            
            if (isSmallMobile) {
                scale = 0.9;
            } else if (isMobile) {
                scale = 1.0;
            } else if (isLargeDesktop) {
                scale = 1.1;
            } else {
                scale = 1.0;
            }
        }
const pdfUrl = '{{ asset('storage/' . $product->pdf_file) }}';
        // Load the PDF
        async function loadPDF() {
            try {
                console.log('Starting PDF load...');
                const loadingUrl = pdfUrl;
                console.log('Loading PDF from:', loadingUrl);
                
                // Show loading progress
                const loadingElement = pdfCanvas.querySelector('.loading');
                if (loadingElement) {
                    loadingElement.innerHTML = `
                        <div class="spinner"></div>
                        <p>Loading PDF...</p>
                        <div class="loading-progress">
                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>
                        </div>
                    `;
                }
                
                pdfDoc = await pdfjsLib.getDocument(loadingUrl).promise;
                console.log('PDF loaded successfully, pages:', pdfDoc.numPages);
                
                // Update loading message
                if (loadingElement) {
                    loadingElement.innerHTML = `
                        <div class="spinner"></div>
                        <p>Rendering pages...</p>
                        <div class="loading-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 100%"></div>
                            </div>
                        </div>
                    `;
                }
                
                pageInfo.textContent = `Page 1 of ${pdfDoc.numPages}`;
                
                // Enable/disable navigation buttons
                updateNavigationButtons();
                
                // Render first page
                console.log('Rendering first page...');
                await renderPage(pageNum);
                
                // Generate page previews
                console.log('Generating page previews...');
                await generatePagePreviews();
                
                // Fade out loading screen
                if (loadingElement) {
                    loadingElement.style.transition = 'all 0.8s ease-out';
                    loadingElement.style.opacity = '0';
                    loadingElement.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        loadingElement.remove();
                    }, 800);
                }
                
                console.log('PDF viewer initialization complete');
                
            } catch (error) {
                console.error('Error loading PDF:', error);
                pdfCanvas.innerHTML = `
                    <div class="loading">
                        <p style="color: #f44336;">Error loading PDF. Please check the file path.</p>
                        <p style="font-size: 0.9rem; margin-top: 10px;">Make sure the PDF file is in the same directory as this HTML file.</p>
                        <p style="font-size: 0.8rem; margin-top: 10px;">Error details: ${error.message}</p>
                    </div>
                `;
            }
        }

        // Render a specific page
        async function renderPage(num) {
            pageRendering = true;
            
            try {
                const page = await pdfDoc.getPage(num);
                const viewport = page.getViewport({ scale });
                
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                
                await page.render(renderContext).promise;
                
                // Add fade out effect for current content
                const currentCanvas = pdfCanvas.querySelector('.canvas-container');
                if (currentCanvas) {
                    currentCanvas.style.opacity = '0';
                    currentCanvas.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        // Clear previous content and add new canvas
                        pdfCanvas.innerHTML = '';
                        const canvasContainer = document.createElement('div');
                        canvasContainer.className = 'canvas-container';
                        canvasContainer.style.opacity = '0';
                        canvasContainer.style.transform = 'scale(0.95)';
                        canvasContainer.appendChild(canvas);
                        pdfCanvas.appendChild(canvasContainer);
                        
                        // Animate in the new page
                        setTimeout(() => {
                            canvasContainer.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                            canvasContainer.style.opacity = '1';
                            canvasContainer.style.transform = 'scale(1)';
                        }, 50);
                    }, 200);
                } else {
                    // First page load
                    pdfCanvas.innerHTML = '';
                    const canvasContainer = document.createElement('div');
                    canvasContainer.className = 'canvas-container';
                    canvasContainer.appendChild(canvas);
                    pdfCanvas.appendChild(canvasContainer);
                }
                
                pageNum = num;
                pageInfo.textContent = `Page ${pageNum} of ${pdfDoc.numPages}`;
                
                // Update navigation buttons with animation
                updateNavigationButtons();
                
                // Update active preview
                updateActivePreview();
                
            } catch (error) {
                console.error('Error rendering page:', error);
            }
            
            pageRendering = false;
            
            if (pageNumPending !== null) {
                renderPage(pageNumPending);
                pageNumPending = null;
            }
        }

        // Queue rendering of the next page
        function queueRenderPage(num) {
            if (pageRendering) {
                pageRendering = false;
            } else {
                renderPage(num);
            }
        }

        // Go to previous page
        function onPrevPage() {
            if (pageNum <= 1) return;
            queueRenderPage(pageNum - 1);
        }

        // Go to next page
        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) return;
            queueRenderPage(pageNum + 1);
        }

        // Generate page previews
        async function generatePagePreviews() {
            previewGrid.innerHTML = '';
            
            for (let i = 1; i <= pdfDoc.numPages; i++) {
                try {
                    const page = await pdfDoc.getPage(i);
                    const viewport = page.getViewport({ scale: 0.25 });
                    
                    const previewCanvas = document.createElement('canvas');
                    const previewCtx = previewCanvas.getContext('2d');
                    previewCanvas.width = viewport.width;
                    previewCanvas.height = viewport.height;
                    
                    const renderContext = {
                        canvasContext: previewCtx,
                        viewport: viewport
                    };
                    
                    await page.render(renderContext).promise;
                    
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.style.opacity = '0';
                    previewItem.style.transform = 'scale(0.8) translateY(20px)';
                    previewItem.onclick = () => queueRenderPage(i);
                    
                    if (i === 1) previewItem.classList.add('active');
                    
                    previewItem.innerHTML = `
                        <canvas></canvas>
                        <div class="page-number">Page ${i}</div>
                    `;
                    
                    // Replace the placeholder canvas with the rendered one
                    previewItem.querySelector('canvas').replaceWith(previewCanvas);
                    
                    previewGrid.appendChild(previewItem);
                    
                    // Staggered animation for each preview item
                    setTimeout(() => {
                        previewItem.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                        previewItem.style.opacity = '1';
                        previewItem.style.transform = 'scale(1) translateY(0)';
                    }, i * 100);
                    
                } catch (error) {
                    console.error(`Error generating preview for page ${i}:`, error);
                }
            }
        }

        // Update active preview
        function updateActivePreview() {
            const previewItems = previewGrid.querySelectorAll('.preview-item');
            previewItems.forEach((item, index) => {
                const wasActive = item.classList.contains('active');
                const isActive = index === pageNum - 1;
                
                if (wasActive && !isActive) {
                    // Remove active state with animation
                    item.style.transform = 'scale(1)';
                    item.classList.remove('active');
                } else if (!wasActive && isActive) {
                    // Add active state with animation
                    item.classList.add('active');
                    item.style.transform = 'scale(1.05)';
                    setTimeout(() => {
                        item.style.transform = 'scale(1.02)';
                    }, 200);
                }
            });
        }

        // Update navigation buttons with smooth animations
        function updateNavigationButtons() {
            const prevDisabled = pageNum <= 1;
            const nextDisabled = pageNum >= pdfDoc.numPages;
            
            // Animate previous button
            if (prevBtn.disabled !== prevDisabled) {
                prevBtn.disabled = prevDisabled;
                if (!prevDisabled) {
                    prevBtn.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        prevBtn.style.transform = 'scale(1)';
                    }, 200);
                }
            }
            
            // Animate next button
            if (nextBtn.disabled !== nextDisabled) {
                nextBtn.disabled = nextDisabled;
                if (!nextDisabled) {
                    nextBtn.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        nextBtn.style.transform = 'scale(1)';
                    }, 200);
                }
            }
        }

        // Toggle preview panel
        function togglePreviewPanel() {
            previewPanel.classList.toggle('active');
        }

        // Close preview panel
        function closePreviewPanel() {
            previewPanel.classList.remove('active');
        }

        // Event listeners
        prevBtn.addEventListener('click', onPrevPage);
        nextBtn.addEventListener('click', onNextPage);
        togglePreviewBtn.addEventListener('click', togglePreviewPanel);
        closePreviewBtn.addEventListener('click', closePreviewPanel);

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                onPrevPage();
            } else if (e.key === 'ArrowRight') {
                onNextPage();
            } else if (e.key === 'Escape') {
                closePreviewPanel();
            }
        });

        // Responsive handling
        window.addEventListener('resize', () => {
            adjustScale();
            if (pdfDoc) {
                renderPage(pageNum);
            }
        });

        // Security and Protection Functions
        function disableContextMenu(e) {
            // Allow context menu on interactive elements
            if (e.target.closest('.nav-btn') || e.target.closest('.preview-item') || 
                e.target.closest('.toggle-preview-btn') || e.target.closest('.close-preview-btn')) {
                return true;
            }
            e.preventDefault();
            e.stopPropagation();
            return false;
        }

        function disableKeyboardShortcuts(e) {
            // Check if event exists
            if (!e) return;
            
            // Disable Ctrl+S (Save), Ctrl+Shift+S (Save As), Ctrl+P (Print), F12 (DevTools)
            if ((e.ctrlKey && (e.key === 's' || e.key === 'S')) || 
                (e.ctrlKey && e.shiftKey && (e.key === 's' || e.key === 'S')) ||
                (e.ctrlKey && (e.key === 'p' || e.key === 'P')) ||
                e.key === 'F12' ||
                e.key === 'F5' ||
                (e.ctrlKey && e.key === 'u') ||
                (e.ctrlKey && e.key === 'shift') ||
                (e.ctrlKey && e.key === 'i')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        }

        function disableDevTools() {
            // Detect DevTools opening
            let devtools = { open: false, orientation: null };
            
            setInterval(() => {
                const threshold = 160;
                const widthThreshold = window.outerWidth - window.innerWidth > threshold;
                const heightThreshold = window.outerHeight - window.innerHeight > threshold;
                
                if (widthThreshold || heightThreshold) {
                    if (!devtools.open) {
                        devtools.open = true;
                        document.body.innerHTML = '<div style="display: flex; justify-content: center; align-items: center; height: 100vh; color: white; font-size: 24px;">⚠️ Developer Tools are not allowed on this page</div>';
                    }
                } else {
                    devtools.open = false;
                }
            }, 500);
        }

        function disablePrintScreen() {
            // Disable print screen key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'PrintScreen' || e.key === 'PrtScn') {
                    e.preventDefault();
                    return false;
                }
            });
        }

        function disableRightClick() {
            // Disable right click everywhere
            document.addEventListener('contextmenu', disableContextMenu);
            document.addEventListener('selectstart', disableContextMenu);
            document.addEventListener('dragstart', disableContextMenu);
            document.addEventListener('mousedown', (e) => {
                // Allow right click on interactive elements
                if (e.target.closest('.nav-btn') || e.target.closest('.preview-item') || 
                    e.target.closest('.toggle-preview-btn') || e.target.closest('.close-preview-btn')) {
                    return true;
                }
                if (e.button === 2) { // Right mouse button
                    e.preventDefault();
                    return false;
                }
            });
        }

        function disableTextSelection() {
            // Disable text selection
            document.addEventListener('selectstart', disableContextMenu);
            document.addEventListener('mousedown', (e) => {
                // Allow navigation buttons and preview items to work
                if (e.target.closest('.nav-btn') || e.target.closest('.preview-item') || 
                    e.target.closest('.toggle-preview-btn') || e.target.closest('.close-preview-btn')) {
                    return true;
                }
                if (e.target.tagName === 'CANVAS' || e.target.closest('.canvas-container')) {
                    e.preventDefault();
                    return false;
                }
            });
        }

        function disableScreenshot() {
            // Disable screenshot attempts
            document.addEventListener('keydown', (e) => {
                // Windows: Win+Shift+S, Mac: Cmd+Shift+4
                if ((e.metaKey || e.ctrlKey) && e.shiftKey && (e.key === '4' || e.key === 'S')) {
                    e.preventDefault();
                    return false;
                }
            });
        }

        // Initialize all security measures
        function initializeSecurity() {
            disableRightClick();
            disableTextSelection();
            // Add keyboard event listener properly
            document.addEventListener('keydown', disableKeyboardShortcuts);
            disablePrintScreen();
            disableScreenshot();
            disableDevTools();
        }

        // Load PDF when page loads
        window.addEventListener('load', () => {
            console.log('Page loaded, initializing...');
            initializeSecurity();
            adjustScale();
            const pdfUrl = '{{ asset('storage/' . $product->pdf_file) }}';
            
            // Check if PDF file exists before loading
            fetch(pdfUrl, { method: 'HEAD' })
                .then(response => {
                    if (response.ok) {
                        console.log('PDF file found, loading...');
                        loadPDF();
                    } else {
                        console.error('PDF file not found');
                        pdfCanvas.innerHTML = `
                            <div class="loading">
                                <p style="color: #f44336;">PDF file not found!</p>
                                <p style="font-size: 0.9rem; margin-top: 10px;">Please ensure 'Canadian Living - September 2025_freemagazines.top.pdf' is in the same folder as this HTML file.</p>
                                <p style="font-size: 0.8rem; margin-top: 10px;">Current directory: ${window.location.href}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error checking PDF file:', error);
                    pdfCanvas.innerHTML = `
                        <div class="loading">
                            <p style="color: #f44336;">Error accessing PDF file!</p>
                            <p style="font-size: 0.9rem; margin-top: 10px;">Please check the file path and ensure the PDF is accessible.</p>
                            <p style="font-size: 0.8rem; margin-top: 10px;">Error: ${error.message}</p>
                        </div>
                    `;
                });
        });
    </script>
</body>
</html>