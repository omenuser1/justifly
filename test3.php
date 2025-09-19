
import React, { useState, useEffect } from 'react';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { 
  GraduationCap, 
  Calendar, 
  Bell, 
  BarChart3, 
  Shield, 
  Smartphone,
  FileText,
  ArrowRight,
  Play,
  Sparkles,
  Zap,
  Star,
  Heart,
  Users,
  TrendingUp
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';

const emojis = ['🎓', '📊', '⚡', '🚀', '✨', '🎯'];

const FloatingShapes = () => (
  <>
    <motion.div
      className="absolute top-20 left-10 w-20 h-20 bg-gradient-to-br from-pink-400 to-purple-600 rounded-full opacity-70"
      animate={{ y: [0, -20, 0], rotate: [0, 180, 360] }}
      transition={{ duration: 4, repeat: Infinity, ease: "easeInOut" }}
    />
    <motion.div
      className="absolute top-40 right-20 w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg opacity-60"
      animate={{ y: [0, 20, 0], rotate: [0, -90, 0] }}
      transition={{ duration: 3, repeat: Infinity, ease: "easeInOut", delay: 1 }}
    />
    <motion.div
      className="absolute bottom-40 left-20 w-12 h-12 bg-gradient-to-br from-green-400 to-blue-500 rounded-full opacity-50"
      animate={{ y: [0, -15, 0], x: [0, 10, 0] }}
      transition={{ duration: 5, repeat: Infinity, ease: "easeInOut", delay: 2 }}
    />
    <motion.div
      className="absolute bottom-20 right-10 w-24 h-6 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full opacity-40"
      animate={{ rotate: [0, 360], scale: [1, 1.2, 1] }}
      transition={{ duration: 6, repeat: Infinity, ease: "easeInOut" }}
    />
  </>
);

const InteractiveFeatureCard = ({ feature, index }) => {
  const [isHovered, setIsHovered] = useState(false);
  
  const colors = [
    'from-pink-500 to-purple-600',
    'from-blue-500 to-cyan-500', 
    'from-green-500 to-emerald-500',
    'from-yellow-500 to-orange-500',
    'from-purple-500 to-pink-500',
    'from-cyan-500 to-blue-500'
  ];

  return (
    <motion.div
      initial={{ opacity: 0, y: 100, scale: 0.8 }}
      whileInView={{ opacity: 1, y: 0, scale: 1 }}
      transition={{ duration: 0.6, delay: index * 0.1, type: "spring", bounce: 0.4 }}
      viewport={{ once: true }}
      onHoverStart={() => setIsHovered(true)}
      onHoverEnd={() => setIsHovered(false)}
      className="group cursor-pointer"
    >
      <Card className="relative overflow-hidden bg-white/90 backdrop-blur-sm border-2 border-transparent hover:border-white shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105 hover:-rotate-2">
        <div className={`absolute inset-0 bg-gradient-to-br ${colors[index]} opacity-0 group-hover:opacity-10 transition-opacity duration-500`} />
        
        <CardContent className="p-8 relative z-10">
          <motion.div
            className={`w-16 h-16 rounded-2xl flex items-center justify-center mb-6 bg-gradient-to-br ${colors[index]} shadow-lg`}
            animate={isHovered ? { scale: 1.2, rotate: 15 } : { scale: 1, rotate: 0 }}
            transition={{ type: "spring", stiffness: 300 }}
          >
            <feature.icon className="w-8 h-8 text-white" />
          </motion.div>
          
          <h3 className="text-2xl font-bold text-slate-900 mb-4 group-hover:text-purple-700 transition-colors">
            {feature.title}
          </h3>
          <p className="text-slate-600 leading-relaxed mb-4">{feature.description}</p>
          
          <motion.div
            className="flex items-center text-purple-600 font-semibold group-hover:text-purple-700"
            animate={isHovered ? { x: 5 } : { x: 0 }}
          >
            Learn more <ArrowRight className="w-4 h-4 ml-2" />
          </motion.div>
        </CardContent>
        
        {isHovered && (
          <motion.div
            initial={{ scale: 0 }}
            animate={{ scale: 1 }}
            className="absolute top-4 right-4 w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center"
          >
            <Sparkles className="w-4 h-4 text-white" />
          </motion.div>
        )}
      </Card>
    </motion.div>
  );
};

const AnimatedBackground = () => (
  <div className="fixed inset-0 overflow-hidden -z-10">
    <div className="absolute inset-0 bg-gradient-to-br from-pink-100 via-purple-50 to-cyan-100" />
    
    {/* Animated blobs */}
    <motion.div
      className="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full mix-blend-multiply filter blur-xl opacity-70"
      animate={{
        x: [0, 100, 0],
        y: [0, -100, 0],
        scale: [1, 1.2, 1],
      }}
      transition={{ duration: 20, repeat: Infinity, ease: "easeInOut" }}
    />
    <motion.div
      className="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-70"
      animate={{
        x: [0, -100, 0],
        y: [0, 100, 0],
        scale: [1.2, 1, 1.2],
      }}
      transition={{ duration: 15, repeat: Infinity, ease: "easeInOut" }}
    />
    <motion.div
      className="absolute top-1/2 left-1/2 w-80 h-80 bg-gradient-to-br from-green-400 to-emerald-400 rounded-full mix-blend-multiply filter blur-xl opacity-50"
      animate={{
        x: [-100, 100, -100],
        y: [-100, 100, -100],
        scale: [1, 1.3, 1],
      }}
      transition={{ duration: 25, repeat: Infinity, ease: "easeInOut" }}
    />
  </div>
);

export default function Home() {
  const [currentEmoji, setCurrentEmoji] = useState(0);

  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentEmoji((prev) => (prev + 1) % emojis.length);
    }, 2000);
    return () => clearInterval(interval);
  }, []);

  const features = [
    { 
      icon: Calendar, 
      title: "Smart AI Tracking", 
      description: "Our AI automatically detects patterns and predicts attendance issues before they happen, helping students stay on track!" 
    },
    { 
      icon: Bell, 
      title: "Lightning Fast Alerts", 
      description: "Get instant notifications via SMS, email, and push notifications. Never miss important attendance updates again!" 
    },
    { 
      icon: BarChart3, 
      title: "Beautiful Analytics", 
      description: "Stunning visual dashboards that make data interpretation fun and actionable for everyone in your institution." 
    },
    { 
      icon: Smartphone, 
      title: "Mobile Magic", 
      description: "Gorgeous mobile apps that work seamlessly offline and online. Take attendance anywhere, anytime!" 
    },
    { 
      icon: Shield, 
      title: "Fort Knox Security", 
      description: "Military-grade encryption keeps student data safer than ever. Privacy and compliance made simple." 
    },
    { 
      icon: FileText, 
      title: "One-Click Reports", 
      description: "Generate beautiful, comprehensive reports in seconds. Compliance has never been this easy!" 
    }
  ];

  const stats = [
    { icon: GraduationCap, number: '500+', label: 'Happy Universities', color: 'from-purple-500 to-pink-500' },
    { icon: Users, number: '2M+', label: 'Students Tracked', color: 'from-blue-500 to-cyan-500' },
    { icon: TrendingUp, number: '99.9%', label: 'Uptime Record', color: 'from-green-500 to-emerald-500' },
    { icon: Heart, number: '24/7', label: 'Love & Support', color: 'from-red-500 to-pink-500' }
  ];

  return (
    <div className="min-h-screen relative overflow-hidden">
      <AnimatedBackground />
      <FloatingShapes />

      {/* Hero Section */}
      <section className="relative min-h-screen flex items-center justify-center px-6 lg:px-8 pt-20">
        <div className="max-w-7xl mx-auto text-center relative z-10">
          <motion.div
            initial={{ opacity: 0, scale: 0.8 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.8, type: "spring", bounce: 0.4 }}
          >
            <motion.div
              className="text-8xl mb-8"
              key={currentEmoji}
              initial={{ scale: 0, rotate: -180 }}
              animate={{ scale: 1, rotate: 0 }}
              transition={{ type: "spring", stiffness: 300 }}
            >
              {emojis[currentEmoji]}
            </motion.div>

            <motion.h1
              className="text-6xl lg:text-8xl font-black mb-8 bg-gradient-to-r from-purple-600 via-pink-500 to-cyan-500 bg-clip-text text-transparent leading-tight"
              initial={{ opacity: 0, y: 50 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.2 }}
            >
              JUSTIFLY
            </motion.h1>

            <motion.h2
              className="text-3xl lg:text-5xl font-bold text-slate-800 mb-8"
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.4 }}
            >
              Where Attendance Meets
              <motion.span
                className="block bg-gradient-to-r from-green-500 to-blue-500 bg-clip-text text-transparent"
                animate={{ backgroundPosition: ["0%", "100%", "0%"] }}
                transition={{ duration: 3, repeat: Infinity }}
              >
                Pure Joy! ✨
              </motion.span>
            </motion.h2>

            <motion.p
              className="text-xl lg:text-2xl text-slate-700 mb-12 max-w-4xl mx-auto leading-relaxed"
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.6 }}
            >
              The most delightful way to manage university attendance. 
              Beautiful design meets powerful AI to create an experience students and faculty actually love using!
            </motion.p>

            <motion.div
              className="flex flex-col sm:flex-row gap-6 justify-center items-center"
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.8 }}
            >
              <Button 
                size="lg" 
                className="relative overflow-hidden bg-gradient-to-r from-purple-600 via-pink-500 to-cyan-500 hover:from-purple-700 hover:via-pink-600 hover:to-cyan-600 text-white px-12 py-6 text-xl font-bold rounded-full shadow-2xl shadow-purple-500/30 transform hover:scale-110 transition-all duration-300 group"
              >
                <Play className="w-6 h-6 mr-3" />
                <span className="relative z-10">Watch the Magic</span>
                <div className="absolute inset-0 bg-gradient-to-r from-cyan-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
              </Button>
              
              <Button 
                variant="outline" 
                size="lg" 
                className="border-3 border-purple-500 text-purple-700 hover:bg-purple-50 px-12 py-6 text-xl font-bold rounded-full backdrop-blur-sm transform hover:scale-110 transition-all duration-300 shadow-lg shadow-purple-200/50"
              >
                <Zap className="w-6 h-6 mr-3" />
                Start Free Trial
              </Button>
            </motion.div>

            {/* Stats */}
            <motion.div
              className="grid grid-cols-2 lg:grid-cols-4 gap-8 mt-20 max-w-5xl mx-auto"
              initial={{ opacity: 0, y: 50 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 1 }}
            >
              {stats.map((stat, index) => (
                <motion.div
                  key={index}
                  className="relative group cursor-pointer"
                  whileHover={{ scale: 1.1, y: -10 }}
                  transition={{ type: "spring", stiffness: 300 }}
                >
                  <div className={`absolute inset-0 bg-gradient-to-br ${stat.color} rounded-3xl opacity-20 group-hover:opacity-30 transition-opacity`} />
                  <div className="relative bg-white/80 backdrop-blur-sm p-6 rounded-3xl border border-white/50 shadow-lg">
                    <div className={`w-12 h-12 mx-auto mb-4 rounded-full bg-gradient-to-br ${stat.color} flex items-center justify-center shadow-lg`}>
                      <stat.icon className="w-6 h-6 text-white" />
                    </div>
                    <div className="text-3xl font-black text-slate-800 mb-2">{stat.number}</div>
                    <div className="text-slate-600 font-semibold">{stat.label}</div>
                  </div>
                </motion.div>
              ))}
            </motion.div>
          </motion.div>
        </div>
      </section>

      {/* Features Section */}
      <section className="relative py-32 z-10">
        <div className="max-w-7xl mx-auto px-6 lg:px-8">
          <motion.div
            className="text-center mb-20"
            initial={{ opacity: 0, y: 50 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8 }}
            viewport={{ once: true }}
          >
            <h2 className="text-5xl lg:text-6xl font-black text-slate-800 mb-6">
              Features that
              <span className="block bg-gradient-to-r from-orange-500 to-pink-500 bg-clip-text text-transparent">
                Spark Joy! 🎉
              </span>
            </h2>
            <p className="text-2xl text-slate-600 max-w-3xl mx-auto">
              Every feature designed to make your day brighter and your work effortless
            </p>
          </motion.div>

          <div className="grid lg:grid-cols-3 gap-8">
            {features.map((feature, index) => (
              <InteractiveFeatureCard 
                key={index} 
                feature={feature} 
                index={index}
              />
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="relative py-32 z-10">
        <div className="max-w-5xl mx-auto px-6 lg:px-8 text-center">
          <motion.div
            initial={{ opacity: 0, scale: 0.8 }}
            whileInView={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.8, type: "spring" }}
            viewport={{ once: true }}
            className="relative"
          >
            <div className="absolute inset-0 bg-gradient-to-r from-purple-600/20 via-pink-500/20 to-cyan-500/20 rounded-[3rem] blur-3xl" />
            
            <div className="relative bg-white/20 backdrop-blur-xl border border-white/30 rounded-[3rem] p-16 shadow-2xl">
              <motion.div
                className="text-6xl mb-8"
                animate={{ rotate: [0, 10, -10, 0] }}
                transition={{ duration: 2, repeat: Infinity }}
              >
                🚀
              </motion.div>
              
              <h2 className="text-4xl lg:text-6xl font-black text-slate-800 mb-8">
                Ready to Transform
                <span className="block bg-gradient-to-r from-purple-600 to-cyan-500 bg-clip-text text-transparent">
                  Your University?
                </span>
              </h2>
              
              <p className="text-2xl text-slate-700 mb-12 max-w-3xl mx-auto">
                Join thousands of happy universities already using Justifly! 
                Experience the future of attendance management today.
              </p>
              
              <Button 
                size="lg" 
                className="bg-gradient-to-r from-purple-600 via-pink-500 to-cyan-500 hover:from-purple-700 hover:via-pink-600 hover:to-cyan-600 text-white px-16 py-8 text-2xl font-bold rounded-full shadow-2xl shadow-purple-500/30 transform hover:scale-110 transition-all duration-300"
              >
                <Star className="w-8 h-8 mr-4" />
                Start Your Journey
                <ArrowRight className="w-8 h-8 ml-4" />
              </Button>
            </div>
          </motion.div>
        </div>
      </section>

      {/* Footer */}
      <footer className="relative py-16 border-t border-white/20 backdrop-blur-sm z-10">
        <div className="max-w-7xl mx-auto px-6 lg:px-8 text-center">
          <div className="flex items-center justify-center gap-3 mb-6">
            <motion.div
              animate={{ rotate: 360 }}
              transition={{ duration: 10, repeat: Infinity, ease: "linear" }}
            >
              <GraduationCap className="w-12 h-12 text-purple-600" />
            </motion.div>
            <span className="text-4xl font-black bg-gradient-to-r from-purple-600 to-cyan-500 bg-clip-text text-transparent">
              JUSTIFLY
            </span>
          </div>
          <p className="text-slate-600 text-xl">
            Making university attendance management delightfully simple! 💜
          </p>
        </div>
      </footer>
    </div>
  );
}
